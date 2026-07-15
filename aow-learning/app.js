(async () => {
  const modules = ["ppb", "navigation", "naturalist"];
  const labels = { ppb: "PPB", navigation: "ナビゲーション", naturalist: "ナチュラリスト" };
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || "";
  const adminPreview = document.querySelector('meta[name="admin-preview"]')?.content === "1";
  let learnerId = document.querySelector('meta[name="learner-id"]')?.content || "";
  let state = { modules: {}, ready: {}, completion: null };
  let saveTimer;
  let saveQueue = Promise.resolve();
  let stateRevision = 0;

  try {
    if (adminPreview) throw new DOMException("preview", "AbortError");
    const response = await fetch("api.php", { credentials: "same-origin", headers: { Accept: "application/json" } });
    if (response.status === 401) return void (location.href = "login.php");
    if (!response.ok) throw new Error("load failed");
    const payload = await response.json();
    state = payload.state || state;
    learnerId = payload.learnerId || learnerId;
  } catch (error) {
    if (adminPreview && error?.name === "AbortError") {
      state = { modules: {}, ready: {}, completion: null };
    } else {
      showSyncError("進捗を読み込めませんでした。通信を確認して再読み込みしてください。");
      return;
    }
  }

  function moduleState(name) {
    if (!state.modules[name]) state.modules[name] = { answers: {}, complete: false };
    return state.modules[name];
  }

  function persist(issueCompletion = false) {
    clearTimeout(saveTimer);
    if (adminPreview) {
      if (issueCompletion && allReady() && allModulesComplete()) {
        state.completion = { learnerId, issuedAt: new Date().toISOString(), code: "ADMIN-PREVIEW" };
      }
      renderProgress();
      return Promise.resolve();
    }
    const snapshot = JSON.parse(JSON.stringify(state));
    const revision = stateRevision;
    const request = saveQueue.catch(() => undefined).then(async () => {
      const response = await fetch("api.php", {
        method: "POST",
        credentials: "same-origin",
        headers: { "Content-Type": "application/json", Accept: "application/json" },
        body: JSON.stringify({ csrf, state: snapshot, issueCompletion })
      });
      if (response.status === 401) {
        location.href = "login.php";
        return;
      }
      if (!response.ok) throw new Error("save failed");
      const payload = await response.json();
      if (revision === stateRevision) {
        state = payload.state || state;
      } else if (payload.state?.completion) {
        state.completion = payload.state.completion;
      }
      learnerId = payload.learnerId || learnerId;
      document.getElementById("syncError")?.remove();
      renderProgress();
    });
    saveQueue = request;
    return request;
  }

  function saveSoon() {
    clearTimeout(saveTimer);
    saveTimer = setTimeout(() => persist().catch(() => showSyncError("進捗を保存できませんでした。通信を確認してください。")), 350);
  }

  function showSyncError(message) {
    let note = document.getElementById("syncError");
    if (!note) {
      note = document.createElement("p");
      note.id = "syncError";
      note.style.cssText = "position:fixed;z-index:99;left:16px;right:16px;bottom:82px;margin:auto;max-width:620px;padding:13px 16px;background:#fff0eb;color:#934433;box-shadow:0 8px 30px rgba(0,0,0,.18);font-size:13px";
      document.body.append(note);
    }
    note.textContent = message;
  }

  document.querySelectorAll(".question").forEach((question) => {
    const module = question.closest("[data-module]").dataset.module;
    const qid = question.dataset.question;
    const saved = moduleState(module).answers[qid];
    if (saved) {
      const input = question.querySelector(`input[value="${saved}"]`);
      if (input) input.checked = true;
      showFeedback(question, saved);
    }
    question.addEventListener("change", (event) => {
      if (!event.target.matches("input[type=radio]")) return;
      moduleState(module).answers[qid] = event.target.value;
      if (event.target.value !== question.dataset.answer) moduleState(module).complete = false;
      stateRevision += 1;
      showFeedback(question, event.target.value);
      updateCompleteButton(module);
      renderProgress();
      saveSoon();
    });
  });

  function showFeedback(question, value) {
    const correct = value === question.dataset.answer;
    question.classList.toggle("is-correct", correct);
    question.classList.toggle("is-wrong", !correct);
    const source = question.querySelector(correct ? "[data-correct]" : "[data-wrong]");
    question.querySelector("[data-feedback]").textContent = source.content.textContent.trim();
  }

  function updateCompleteButton(module) {
    const article = document.querySelector(`[data-module="${module}"]`);
    const questions = [...article.querySelectorAll(".question")];
    const allCorrect = questions.every((q) => moduleState(module).answers[q.dataset.question] === q.dataset.answer);
    article.querySelector("[data-complete]").disabled = moduleState(module).complete || !allCorrect;
  }

  document.querySelectorAll("[data-complete]").forEach((button) => {
    const module = button.dataset.complete;
    updateCompleteButton(module);
    button.addEventListener("click", async () => {
      moduleState(module).complete = true;
      stateRevision += 1;
      button.disabled = true;
      try {
        await persist();
        const next = modules[modules.indexOf(module) + 1];
        document.getElementById(next || "finish").scrollIntoView({ behavior: "smooth" });
      } catch (_) {
        moduleState(module).complete = false;
        stateRevision += 1;
        showSyncError("レッスン完了を保存できませんでした。もう一度お試しください。");
        updateCompleteButton(module);
      }
    });
  });

  document.querySelectorAll("[data-ready]").forEach((input) => {
    input.checked = Boolean(state.ready?.[input.dataset.ready]);
    input.addEventListener("change", () => {
      state.ready[input.dataset.ready] = input.checked;
      stateRevision += 1;
      renderProgress();
      saveSoon();
    });
  });

  const issueCompletionButton = document.getElementById("issueCompletion");
  document.getElementById("completionLearnerPreview").textContent = learnerId;
  issueCompletionButton.addEventListener("click", async () => {
    if (!allReady() || !allModulesComplete()) return;
    issueCompletionButton.disabled = true;
    try {
      await persist(true);
      document.getElementById("completionProof").scrollIntoView({ behavior: "smooth", block: "center" });
    } catch (_) {
      showSyncError("修了情報を発行できませんでした。もう一度お試しください。");
      renderProgress();
    }
  });

  function allReady() {
    return ["gear", "condition", "question"].every((key) => Boolean(state.ready?.[key]));
  }

  function allModulesComplete() {
    return modules.every((name) => moduleState(name).complete);
  }

  function renderCompletion(completed) {
    issueCompletionButton.disabled = completed !== 3 || !allReady() || Boolean(state.completion);
    issueCompletionButton.textContent = state.completion ? "修了記録 発行済み ✓" : "事前学科の修了画面を発行";
    const proof = document.getElementById("completionProof");
    const valid = completed === 3 && state.completion;
    proof.hidden = !valid;
    if (!valid) return;
    document.getElementById("completionName").textContent = state.completion.learnerId || learnerId;
    document.getElementById("completionDate").textContent = new Intl.DateTimeFormat("ja-JP", { dateStyle: "long", timeStyle: "short" }).format(new Date(state.completion.issuedAt));
    document.getElementById("completionCode").textContent = state.completion.code;
  }

  function renderProgress() {
    const completed = modules.filter((name) => moduleState(name).complete).length;
    const percentage = Math.round((completed / modules.length) * 100);
    document.getElementById("progressNumber").textContent = percentage;
    document.getElementById("progressRing").style.setProperty("--progress", percentage);
    document.getElementById("mobileProgress").textContent = `${completed} / 3 完了`;
    document.getElementById("progressMessage").textContent = completed === 3 ? "事前学習が完了しました" : completed ? `あと${3 - completed}レッスンです` : "最初のレッスンから始めましょう";
    modules.forEach((name) => {
      const done = moduleState(name).complete;
      document.querySelectorAll(`[data-card="${name}"], [data-nav="${name}"]`).forEach((el) => el.classList.toggle("is-done", done));
      const status = document.querySelector(`[data-status="${name}"]`);
      if (status) status.textContent = done ? "完了" : Object.keys(moduleState(name).answers).length ? "学習中" : "未開始";
      const button = document.querySelector(`[data-complete="${name}"]`);
      if (button) {
        button.textContent = done ? `${labels[name]} 完了 ✓` : `${labels[name]}を完了する`;
        button.classList.toggle("is-done", done);
      }
    });
    const nextName = modules.find((name) => !moduleState(name).complete);
    const nextLink = document.getElementById("nextLesson");
    nextLink.href = nextName ? `#${nextName}` : "#finish";
    nextLink.textContent = nextName ? (completed ? "続きから学ぶ" : "最初のレッスンへ") : "最終チェックへ";
    const checklist = document.getElementById("dayChecklist");
    checklist.hidden = completed !== 3;
    document.getElementById("finishText").textContent = completed === 3 ? "3科目すべての知識確認が完了しました。最終チェック後に、サーバーで修了記録を発行してください。" : "3レッスンを完了すると、修了画面を発行できます。";
    renderCompletion(completed);
  }

  renderProgress();
})();
