/* ==========================================================================
   三浦 海の学校 — 予約・お問い合わせフォーム（contact/）
   ・?from= … 流入元を hidden の ref に（英数字・ハイフン・下線のみ・32字まで）
   ・?category=体験ダイビング&date=2026-10-04 … 種別と希望日を事前入力
   ・?people=2名&msg=… … 人数と内容も事前入力（相談シート js/ask.js から来たとき）
   ・種別・希望日・人数をえらぶと、内容の欄に下書きを入れる（自分で書いた文には触らない）
   ・送信前にサーバー発行のトークンを取得。入力内容を保ったままエラーを表示する。
   項目名 name/kana/email/phone/category/date/people/message/ref は変えない。
   ========================================================================== */
(function () {
  'use strict';
  var params = new URLSearchParams(window.location.search);
  var form = document.getElementById('contactForm');
  if (!form) return;

  var from = (params.get('from') || '').replace(/[^a-zA-Z0-9_-]/g, '').slice(0, 32);
  if (from) document.getElementById('refSource').value = from;

  var cat = params.get('category');
  if (cat) {
    var radios = form.querySelectorAll('input[name="category"]');
    for (var i = 0; i < radios.length; i++) {
      if (radios[i].value === cat) { radios[i].checked = true; break; }
    }
  }

  var date = document.getElementById('date');
  if (date) {
    /* 過去の日付は選べないように（日本時間の今日から） */
    var n = new Date(), j = new Date(n.getTime() + n.getTimezoneOffset() * 60000 + 9 * 3600000);
    var pad = function (x) { return (x < 10 ? '0' : '') + x; };
    date.min = j.getFullYear() + '-' + pad(j.getMonth() + 1) + '-' + pad(j.getDate());
    var d = params.get('date') || '';
    if (/^\d{4}-\d{2}-\d{2}$/.test(d)) date.value = d;
  }

  /* 人数と内容の事前入力（相談シートから） */
  var people = document.getElementById('people');
  var pv = params.get('people') || '';
  if (people && pv) {
    for (var k = 0; k < people.options.length; k++) {
      if (people.options[k].value === pv) { people.value = pv; break; }
    }
  }
  var msgField = document.getElementById('message');
  var pm = (params.get('msg') || '').replace(/\r\n?/g, '\n').slice(0, 600);
  if (msgField && pm && !msgField.value) msgField.value = pm;
  if (pm && form.firstElementChild) {
    var pre = document.createElement('p');
    pre.className = 'cform__pre';
    pre.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm-1.2 14.2-4-4 1.4-1.4 2.6 2.6 5.4-5.4 1.4 1.4-6.8 6.8z"/></svg>' +
      '<span>えらんだ内容を入れてあります。<span class="nw">お名前とメールアドレスを入れて、</span><span class="nw">送信してください。</span></span>';
    var firstGroup = form.querySelector('.cform__group');
    if (firstGroup) form.insertBefore(pre, firstGroup);
  }

  /* 内容の下書き：種別・希望日・人数から、送る文を作っておく。
     入れるのは、欄が空のときと、前に入れた下書きのままのときだけ。 */
  var DRAFT = {
    '体験ダイビング': '体験ダイビングの空き日程を知りたいです。',
    'OWDライセンス': 'ライセンス講習（オープンウォーター）の日程を相談したいです。',
    'AOWライセンス': 'アドバンス・スペシャルティ講習の日程を相談したいです。',
    'ファンダイビング': 'ファンダイビングの空き日程を知りたいです。',
    'リフレッシュ': 'しばらく潜っていないので、リフレッシュダイビングを相談したいです。',
    'マリンアクティビティ': 'スノーケリングの空き日程を知りたいです。'
  };
  var WD = ['日', '月', '火', '水', '木', '金', '土'];
  var lastDraft = '';
  var draftHint = document.getElementById('draftHint');
  function draft() {
    if (!msgField) return;
    var cur = msgField.value;
    if (cur && cur !== lastDraft) return;
    var c = form.querySelector('input[name="category"]:checked');
    var base = c && DRAFT[c.value];
    if (!base) {
      if (lastDraft && cur === lastDraft) msgField.value = '';
      lastDraft = '';
      if (draftHint) draftHint.hidden = true;
      return;
    }
    var tail = '', dv = date && date.value, pp = people && people.value, dt = '';
    if (/^\d{4}-\d{2}-\d{2}$/.test(dv || '')) {
      var q = dv.split('-'), dd = new Date(+q[0], +q[1] - 1, +q[2]);
      dt = (dd.getMonth() + 1) + '/' + dd.getDate() + '(' + WD[dd.getDay()] + ')';
    }
    if (dt && pp) tail = '希望日は' + dt + '、人数は' + pp + 'です。';
    else if (dt) tail = '希望日は' + dt + 'です。';
    else if (pp) tail = '人数は' + pp + 'です。';
    msgField.value = lastDraft = base + tail;
    if (draftHint) draftHint.hidden = false;
  }
  form.addEventListener('change', function (e) {
    var t = e.target;
    if (t && (t.name === 'category' || t.id === 'date' || t.id === 'people')) draft();
  });
  if (msgField) msgField.addEventListener('input', function () { if (draftHint && msgField.value !== lastDraft) draftHint.hidden = true; });
  form.addEventListener('reset', function () { lastDraft = ''; if (draftHint) draftHint.hidden = true; });
  if (cat && !pm) draft();

  var errorBox = document.getElementById('formError');
  var successBox = document.getElementById('formSuccess');
  var button = form.querySelector('button[type="submit"]');
  var tokenField = document.getElementById('csrfToken');
  var tokenReadyAt = 0;
  var tokenExpiresAt = 0;
  var tokenRequest = null;
  var submitting = false;

  function reveal(box) {
    box.hidden = false;
    box.setAttribute('tabindex', '-1');
    box.focus({ preventScroll: true });
    box.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
  function showError(message) {
    errorBox.textContent = message;
    reveal(errorBox);
  }
  function showSuccess() {
    errorBox.hidden = true;
    form.hidden = true;
    reveal(successBox);
  }

  // 旧フォームからの戻りURLでも、従来の完了・エラー表示を維持する。
  var status = params.get('status');
  if (status === 'success') {
    showSuccess();
    return;
  }
  if (status === 'error') {
    var reason = params.get('reason') || '';
    var msg = 'エラーが発生しました。お手数ですが、もう一度お試しいただくか、LINEからお問い合わせください。';
    if (reason === 'rate') msg = '短い時間に何度も送信されています。時間をおいて再度お試しいただくか、LINEからお問い合わせください。';
    if (reason === 'validation') msg = '入力内容に不備がありました。必須の項目（種別・お名前・メールアドレス・内容）をご確認ください。';
    if (reason === 'send') msg = 'メールの送信に失敗しました。お手数ですが、LINEからお問い合わせください。';
    if (reason === 'csrf') msg = '送信の確認期限が切れました。内容をご確認のうえ、もう一度送信してください。';
    showError(msg);
  }

  function clearToken() {
    tokenField.value = '';
    tokenReadyAt = 0;
    tokenExpiresAt = 0;
  }
  function loadToken() {
    if (tokenRequest) return tokenRequest;
    clearToken();
    tokenRequest = (async function () {
      var controller = new AbortController();
      var timeout = setTimeout(function () { controller.abort(); }, 15000);
      try {
        var url = new URL(form.action, window.location.href);
        url.searchParams.set('action', 'token');
        var response = await fetch(url, {
          credentials: 'same-origin', cache: 'no-store',
          headers: { Accept: 'application/json' }, signal: controller.signal
        });
        var data = await response.json();
        if (!response.ok || data.success !== true || !/^[a-f0-9]{64}$/i.test(data.token || '')) {
          throw new Error('Token unavailable');
        }
        tokenField.value = data.token;
        tokenReadyAt = Date.now() + Math.max(3, Number(data.min_wait) || 3) * 1000;
        tokenExpiresAt = Date.now() + Math.max(0, (Number(data.expires_in) || 7200) - 10) * 1000;
      } finally {
        clearTimeout(timeout);
      }
    })().finally(function () { tokenRequest = null; });
    return tokenRequest;
  }
  // 入力中に準備する。失敗しても入力は続けられ、送信操作で取得を再試行する。
  loadToken().catch(function () {});

  form.addEventListener('submit', async function (event) {
    event.preventDefault();
    if (submitting || !form.reportValidity()) return;
    submitting = true;
    button.disabled = true;
    var label = button.innerHTML;
    button.textContent = '送信の準備をしています…';
    errorBox.hidden = true;
    var postStarted = false;
    try {
      if (!tokenField.value || Date.now() >= tokenExpiresAt) await loadToken();
      var wait = tokenReadyAt - Date.now();
      if (wait > 0) await new Promise(function (resolve) { setTimeout(resolve, wait); });
      button.textContent = '送信しています…';
      postStarted = true;
      var controller = new AbortController();
      var timeout = setTimeout(function () { controller.abort(); }, 30000);
      var response, data;
      try {
        response = await fetch(form.action, {
          method: 'POST', body: new FormData(form), credentials: 'same-origin',
          headers: { Accept: 'application/json' }, signal: controller.signal
        });
        data = await response.json().catch(function () { return {}; });
      } finally {
        clearTimeout(timeout);
      }
      if (response.ok && data.success === true) {
        form.reset();
        showSuccess();
      } else {
        showError(data.message || '送信できませんでした。入力内容はそのままで、時間をおいてお試しいただくか、LINEからお問い合わせください。');
      }
    } catch (error) {
      showError(postStarted
        ? '送信結果を確認できませんでした。確認メールが届いていない場合は、時間をおいて再度お試しいただくか、LINEまたは info@miura-diving.com へご連絡ください。'
        : '送信の準備ができませんでした。Cookieを有効にして再度お試しいただくか、LINEまたは info@miura-diving.com へご連絡ください。');
    } finally {
      // 通信の結果が不明な場合もトークンを取り直す。入力内容は自動再送しない。
      if (postStarted) loadToken().catch(function () {});
      submitting = false;
      button.disabled = false;
      button.innerHTML = label;
    }
  });
})();
