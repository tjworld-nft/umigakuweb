(() => {
  const modules = ["ppb", "navigation", "naturalist", "deep", "boat"];
  const legacyModules = ["ppb", "navigation", "naturalist"];
  const labels = { ppb: "PPB", navigation: "ナビゲーション", naturalist: "ナチュラリスト", deep: "ディープ", boat: "ボート" };
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || "";
  const adminPreview = document.querySelector('meta[name="admin-preview"]')?.content === "1";
  let learnerId = document.querySelector('meta[name="learner-id"]')?.content || "";
  let state = { modules: {}, ready: {}, completion: null };
  let saveTimer;
  let saveQueue = Promise.resolve();
  let stateRevision = 0;
  // 初回同期が終わるまでは保存しない。途中の状態を送ると
  // サーバー側は「送られてきた状態が全部」として上書きするので、既存の進捗を消してしまう。
  let loaded = false;
  let pendingWhileLoading = false;
  let savedNoticeTimer;
  let savingNoticeTimer;

  const issueCompletionButton = document.getElementById("issueCompletion");
  const syncStatus = createSyncStatus();

  /* ---------- 進捗の入れ物 ---------- */

  function moduleState(name) {
    const current = state.modules[name];
    if (!current || typeof current !== "object") {
      state.modules[name] = { answers: {}, complete: false };
    } else if (!current.answers || typeof current.answers !== "object" || Array.isArray(current.answers)) {
      // 配列で届くと "ppb1" のような文字列キーが JSON.stringify で消え、保存されない。
      current.answers = Object.assign({}, current.answers);
    }
    return state.modules[name];
  }

  function readyState() {
    if (!state.ready || typeof state.ready !== "object" || Array.isArray(state.ready)) {
      state.ready = Object.assign({}, state.ready);
    }
    return state.ready;
  }

  // 読み込み中に答えた分を捨てずにサーバーの記録と合わせる。
  function mergeServerState(incoming) {
    const merged = incoming && typeof incoming === "object" ? incoming : {};
    const remoteModules = merged.modules && typeof merged.modules === "object" ? merged.modules : {};
    const nextModules = {};
    modules.forEach((name) => {
      const remote = remoteModules[name] && typeof remoteModules[name] === "object" ? remoteModules[name] : {};
      const remoteAnswers = remote.answers && typeof remote.answers === "object" ? remote.answers : {};
      const local = state.modules[name];
      nextModules[name] = {
        answers: Object.assign({}, remoteAnswers, local ? local.answers : null),
        complete: Boolean(remote.complete) || Boolean(local && local.complete)
      };
    });
    merged.modules = nextModules;
    merged.ready = Object.assign({}, merged.ready, state.ready);
    state = merged;
  }

  /* ---------- 保存状況の表示 ---------- */

  function createSyncStatus() {
    const box = document.createElement("div");
    box.id = "syncStatus";
    box.className = "sync-status";
    box.setAttribute("role", "status");
    box.setAttribute("aria-live", "polite");
    box.hidden = true;
    box.innerHTML = '<span data-sync-text></span><a data-sync-action hidden></a>';
    document.body.append(box);
    return box;
  }

  function setSyncStatus(kind, message, action) {
    clearTimeout(savedNoticeTimer);
    clearTimeout(savingNoticeTimer);
    syncStatus.hidden = false;
    syncStatus.classList.remove("is-saving", "is-saved", "is-error");
    syncStatus.classList.add("is-" + kind);
    syncStatus.querySelector("[data-sync-text]").textContent = message;
    const link = syncStatus.querySelector("[data-sync-action]");
    if (action) {
      link.textContent = action.label;
      link.href = action.href;
      link.hidden = false;
    } else {
      link.hidden = true;
      link.removeAttribute("href");
    }
    if (kind === "saved") savedNoticeTimer = setTimeout(hideSyncStatus, 2200);
  }

  function hideSyncStatus() {
    clearTimeout(savedNoticeTimer);
    clearTimeout(savingNoticeTimer);
    syncStatus.hidden = true;
  }

  // すぐ終わる保存で「保存中…」がちらつかないよう、遅いときだけ出す。
  function noticeSavingIfSlow() {
    clearTimeout(savingNoticeTimer);
    savingNoticeTimer = setTimeout(() => setSyncStatus("saving", "保存中…"), 400);
  }

  function signInAgain(message) {
    setSyncStatus("error", message, { label: "ログインし直す", href: "login.php" });
  }

  /* ---------- サーバーとのやり取り ---------- */

  function buildPayload(issueCompletion) {
    return JSON.stringify({ csrf, state: JSON.parse(JSON.stringify(state)), issueCompletion });
  }

  function persist(issueCompletion = false) {
    clearTimeout(saveTimer);
    saveTimer = undefined;
    if (adminPreview) {
      if (issueCompletion && allReady() && allModulesComplete()) {
        state.completion = { learnerId, issuedAt: new Date().toISOString(), code: "ADMIN-PREVIEW", curriculumVersion: 2 };
      }
      renderProgress();
      return Promise.resolve();
    }
    if (!loaded) {
      pendingWhileLoading = true;
      return Promise.resolve();
    }
    const body = buildPayload(issueCompletion);
    const revision = stateRevision;
    noticeSavingIfSlow();
    const request = saveQueue.catch(() => undefined).then(async () => {
      const response = await fetch("api.php", {
        method: "POST",
        credentials: "same-origin",
        headers: { "Content-Type": "application/json", Accept: "application/json" },
        body
      });
      if (response.status === 401 || response.status === 419) {
        // 黙ってログイン画面へ飛ばすと、画面に出ている回答ごと消えて理由も分からない。
        signInAgain("ログインの有効期限が切れました。この画面の回答はまだ保存されていません。");
        throw new Error("session expired");
      }
      if (!response.ok) throw new Error("save failed");
      const payload = await response.json();
      if (revision === stateRevision) {
        state = payload.state || state;
      } else if (payload.state?.completion) {
        state.completion = payload.state.completion;
      }
      learnerId = payload.learnerId || learnerId;
      setSyncStatus("saved", "保存しました");
      renderProgress();
    });
    saveQueue = request;
    return request;
  }

  function saveSoon() {
    clearTimeout(saveTimer);
    if (!loaded) {
      pendingWhileLoading = true;
      return;
    }
    saveTimer = setTimeout(() => {
      saveTimer = undefined;
      persist().catch((error) => {
        if (error?.message !== "session expired") {
          setSyncStatus("error", "進捗を保存できませんでした。通信を確認してください。");
        }
      });
    }, 350);
  }

  // 画面を閉じる・アプリを切り替える瞬間に、待機中の保存を取りこぼさない。
  function flushPendingSave() {
    if (adminPreview || !loaded || !saveTimer) return;
    clearTimeout(saveTimer);
    saveTimer = undefined;
    const body = buildPayload(false);
    if (navigator.sendBeacon && navigator.sendBeacon("api.php", new Blob([body], { type: "application/json" }))) return;
    try {
      fetch("api.php", {
        method: "POST",
        credentials: "same-origin",
        keepalive: true,
        headers: { "Content-Type": "application/json" },
        body
      });
    } catch (_) {
      /* 離脱時なので握りつぶす */
    }
  }

  window.addEventListener("pagehide", flushPendingSave);
  document.addEventListener("visibilitychange", () => {
    if (document.visibilityState === "hidden") flushPendingSave();
  });

  /* ---------- 設問 ---------- */

  const questionNodes = [...document.querySelectorAll(".question")];

  questionNodes.forEach((question) => {
    const module = question.closest("[data-module]").dataset.module;
    const qid = question.dataset.question;
    // 正誤の解説は選んだ直後に差し込まれる。読み上げにも届くようにしておく。
    question.querySelector("[data-feedback]")?.setAttribute("aria-live", "polite");
    question.addEventListener("change", (event) => {
      if (!event.target.matches("input[type=radio]")) return;
      moduleState(module).answers[qid] = event.target.value;
      if (event.target.value !== question.dataset.answer) moduleState(module).complete = false;
      stateRevision += 1;
      showFeedback(question, event.target.value);
      renderProgress();
      saveSoon();
    });
  });

  function restoreAnswers() {
    questionNodes.forEach((question) => {
      const module = question.closest("[data-module]").dataset.module;
      const saved = moduleState(module).answers[question.dataset.question];
      if (!saved) return;
      const input = question.querySelector(`input[value="${saved}"]`);
      if (input) input.checked = true;
      showFeedback(question, saved);
    });
  }

  function showFeedback(question, value) {
    const correct = value === question.dataset.answer;
    question.classList.toggle("is-correct", correct);
    question.classList.toggle("is-wrong", !correct);
    const source = question.querySelector(correct ? "[data-correct]" : "[data-wrong]");
    question.querySelector("[data-feedback]").textContent = source.content.textContent.trim();
  }

  function remainingQuestions(module) {
    const article = document.querySelector(`[data-module="${module}"]`);
    if (!article) return 0;
    return [...article.querySelectorAll(".question")]
      .filter((q) => moduleState(module).answers[q.dataset.question] !== q.dataset.answer).length;
  }

  function updateCompleteButton(module) {
    const article = document.querySelector(`[data-module="${module}"]`);
    const button = article?.querySelector("[data-complete]");
    if (!button) return;
    button.disabled = !loaded || moduleState(module).complete || remainingQuestions(module) > 0;
  }

  document.querySelectorAll("[data-complete]").forEach((button) => {
    const module = button.dataset.complete;
    updateCompleteButton(module);
    button.addEventListener("click", async () => {
      if (button.disabled) return;
      moduleState(module).complete = true;
      stateRevision += 1;
      button.disabled = true;
      try {
        await persist();
        if (!moduleState(module).complete) {
          // 通信は成功したのにサーバーが完了を認めなかった場合。黙って戻すと原因が分からない。
          setSyncStatus("error", "レッスン完了を保存できませんでした。回答を確認して、もう一度お試しください。");
          updateCompleteButton(module);
          return;
        }
        const next = modules[modules.indexOf(module) + 1];
        document.getElementById(next || "finish").scrollIntoView({ behavior: "smooth" });
      } catch (error) {
        moduleState(module).complete = false;
        stateRevision += 1;
        if (error?.message !== "session expired") {
          setSyncStatus("error", "レッスン完了を保存できませんでした。もう一度お試しください。");
        }
        updateCompleteButton(module);
      }
    });
  });

  /* ---------- 最終チェックと修了 ---------- */

  document.querySelectorAll("[data-ready]").forEach((input) => {
    input.addEventListener("change", () => {
      readyState()[input.dataset.ready] = input.checked;
      stateRevision += 1;
      renderProgress();
      saveSoon();
    });
  });

  function restoreReady() {
    document.querySelectorAll("[data-ready]").forEach((input) => {
      input.checked = Boolean(readyState()[input.dataset.ready]);
    });
  }

  issueCompletionButton.addEventListener("click", async () => {
    if (issueCompletionButton.disabled || !allReady() || !allModulesComplete()) return;
    issueCompletionButton.disabled = true;
    try {
      await persist(true);
      document.getElementById("completionProof").scrollIntoView({ behavior: "smooth", block: "center" });
    } catch (error) {
      if (error?.message !== "session expired") {
        setSyncStatus("error", "修了情報を発行できませんでした。もう一度お試しください。");
      }
      renderProgress();
    }
  });

  function allReady() {
    return ["gear", "condition", "question"].every((key) => Boolean(readyState()[key]));
  }

  function requiredModules() {
    const version = Number(state.completion?.curriculumVersion || state.curriculumVersion || 2);
    return state.completion && version === 1 ? legacyModules : modules;
  }

  function allModulesComplete() {
    return requiredModules().every((name) => moduleState(name).complete);
  }

  function renderCompletion(completed, required) {
    const legacyCompletion = Boolean(state.completion) && Number(state.completion.curriculumVersion || state.curriculumVersion || 2) === 1;
    issueCompletionButton.disabled = !loaded || completed !== required.length || !allReady() || Boolean(state.completion);
    issueCompletionButton.textContent = state.completion ? "修了記録 発行済み ✓" : "事前学科の修了画面を発行";
    const proof = document.getElementById("completionProof");
    const valid = Boolean(state.completion);
    proof.hidden = !valid;
    if (!valid) return;
    document.getElementById("completionName").textContent = state.completion.learnerId || learnerId;
    document.getElementById("completionDate").textContent = new Intl.DateTimeFormat("ja-JP", { dateStyle: "long", timeStyle: "short" }).format(new Date(state.completion.issuedAt));
    document.getElementById("completionCode").textContent = state.completion.code;
    document.getElementById("completionSummary").textContent = legacyCompletion
      ? "PPB・アンダーウォーター・ナビゲーション・アンダーウォーター・ナチュラリストの全21問を修了した旧3科目版の記録です。ディープとボートは追加教材として閲覧できます。"
      : "PPB・アンダーウォーター・ナビゲーション・アンダーウォーター・ナチュラリスト・ディープ・ボートの全41問に正解しました。この画面を講習当日に提示してください。";
  }

  function renderProgress() {
    const required = requiredModules();
    const completed = state.completion
      ? required.length
      : required.filter((name) => moduleState(name).complete).length;
    const percentage = Math.round((completed / required.length) * 100);
    const legacyCompletion = Boolean(state.completion) && required.length === legacyModules.length;
    document.getElementById("progressNumber").textContent = percentage;
    document.getElementById("progressRing").style.setProperty("--progress", percentage);
    document.getElementById("mobileProgress").textContent = `${completed} / ${required.length} 完了`;
    document.getElementById("progressMessage").textContent = completed === required.length ? "事前学習が完了しました" : completed ? `あと${required.length - completed}レッスンです` : "最初のレッスンから始めましょう";
    modules.forEach((name) => {
      const lockedByCompletion = Boolean(state.completion) && required.includes(name);
      const done = lockedByCompletion || moduleState(name).complete;
      const additional = legacyCompletion && !required.includes(name);
      document.querySelectorAll(`[data-card="${name}"], [data-nav="${name}"]`).forEach((el) => el.classList.toggle("is-done", done));
      document.querySelectorAll(`[data-module="${name}"] input[type="radio"]`).forEach((input) => {
        input.disabled = lockedByCompletion;
      });
      const answered = Object.keys(moduleState(name).answers).length;
      const status = document.querySelector(`[data-status="${name}"]`);
      if (status) status.textContent = done ? "完了" : answered ? "学習中" : additional ? "追加教材" : "未開始";
      const button = document.querySelector(`[data-complete="${name}"]`);
      if (button) {
        const remaining = remainingQuestions(name);
        // 押せない理由を書いておく。何も言わずに灰色のままだと、詰まったように見える。
        button.textContent = done
          ? `${labels[name]} 完了 ✓`
          : remaining
            ? `${labels[name]}を完了する（正解あと${remaining}問）`
            : `${labels[name]}を完了する`;
        button.classList.toggle("is-done", done);
        // 保存結果を反映し直す。ここで戻さないと、サーバーが完了を認めなかったときに
        // ボタンが押せないまま固まり、再読み込みするまで先へ進めなくなる。
        updateCompleteButton(name);
        if (lockedByCompletion) button.disabled = true;
      }
    });
    const nextName = modules.find((name) => {
      const lockedByCompletion = Boolean(state.completion) && required.includes(name);
      return !lockedByCompletion && !moduleState(name).complete;
    });
    const nextLink = document.getElementById("nextLesson");
    nextLink.href = nextName ? `#${nextName}` : "#finish";
    if (!nextName) {
      nextLink.textContent = "最終チェックへ";
    } else if (legacyCompletion && !required.includes(nextName)) {
      nextLink.textContent = "追加教材を見る";
    } else {
      nextLink.textContent = completed ? "続きから学ぶ" : "最初のレッスンへ";
    }
    const checklist = document.getElementById("dayChecklist");
    checklist.hidden = completed !== required.length;
    document.getElementById("finish-title").textContent = legacyCompletion ? "事前学科は修了済みです。" : "5レッスン全問正解で、事前学科修了。";
    document.getElementById("finishText").textContent = legacyCompletion
      ? "旧3科目版の修了記録はそのまま有効です。ディープとボートは追加教材として学習できます。"
      : completed === required.length
        ? "5科目すべての知識確認が完了しました。最終チェック後に、サーバーで修了記録を発行してください。"
        : "5レッスンを完了すると、修了画面を発行できます。";
    renderCompletion(completed, required);
  }

  /* ---------- レッスンタブ ---------- */

  const lessonNav = document.querySelector(".lesson-nav");
  if (lessonNav) {
    const markScrollEnd = () => {
      const atEnd = lessonNav.scrollLeft + lessonNav.clientWidth >= lessonNav.scrollWidth - 2;
      lessonNav.classList.toggle("is-scroll-end", atEnd);
    };
    lessonNav.addEventListener("scroll", markScrollEnd, { passive: true });
    window.addEventListener("resize", markScrollEnd);
    markScrollEnd();
  }

  /* ---------- 起動 ---------- */

  // 設問の操作はここまでで有効になっている。通信の往復を待つ間に選んだ答えも取りこぼさない。
  document.getElementById("completionLearnerPreview").textContent = learnerId;
  renderProgress();

  async function load() {
    if (adminPreview) {
      state = { modules: {}, ready: {}, completion: null };
      loaded = true;
      renderProgress();
      return;
    }
    setSyncStatus("saving", "進捗を読み込んでいます…");
    try {
      const response = await fetch("api.php", { credentials: "same-origin", headers: { Accept: "application/json" } });
      if (response.status === 401) {
        location.href = "login.php";
        return;
      }
      if (!response.ok) throw new Error("load failed");
      const payload = await response.json();
      mergeServerState(payload.state);
      learnerId = payload.learnerId || learnerId;
    } catch (_) {
      loadFailed = true;
      setSyncStatus("error", "進捗を読み込めませんでした。通信を確認してください。", { label: "再読み込み", href: location.href });
      return;
    }
    loaded = true;
    hideSyncStatus();
    document.getElementById("completionLearnerPreview").textContent = learnerId;
    restoreAnswers();
    restoreReady();
    renderProgress();
    if (pendingWhileLoading) {
      pendingWhileLoading = false;
      saveSoon();
    }
  }

  load();
})();
