/* ==========================================================================
   三浦 海の学校 — 予約・お問い合わせフォーム（contact/）
   ・?from= … 流入元を hidden の ref に（英数字・ハイフン・下線のみ・32字まで）→ 通知メールの【流入元】
   ・?category=体験ダイビング&date=2026-10-04 … 種別と希望日を事前入力（トップや各ページのボタンから）
   ・?status=success|error&reason=… … send_mail.php からの戻り
   送信そのものは send_mail.php（項目名 name/kana/email/phone/category/date/people/message は変えない）
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

  var status = params.get('status');
  if (status === 'success') {
    form.hidden = true;
    var ok = document.getElementById('formSuccess');
    ok.hidden = false;
    setTimeout(function () { ok.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 300);
  } else if (status === 'error') {
    var reason = params.get('reason') || '';
    var msg = 'エラーが発生しました。お手数ですが、もう一度お試しいただくか、LINEからお問い合わせください。';
    if (reason === 'rate') msg = '送信回数の制限に達しました。1分ほど待ってから、もう一度お試しください。';
    if (reason === 'validation') msg = '入力内容に不備がありました。必須の項目（種別・お名前・メールアドレス・内容）をご確認ください。';
    if (reason === 'send') msg = 'メールの送信に失敗しました。お手数ですが、LINEからお問い合わせください。';
    if (reason === 'csrf') msg = 'ページの有効期限が切れました。ページを読み込み直してから、もう一度送信してください。';
    var box = document.getElementById('formError');
    box.textContent = msg;
    box.hidden = false;
    setTimeout(function () { box.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 300);
  }

  /* 二重送信の防止（送信中はボタンを押せなくする） */
  form.addEventListener('submit', function () {
    var b = form.querySelector('button[type="submit"]');
    if (b) { b.disabled = true; b.style.opacity = '.7'; b.lastChild.textContent = '送信しています…'; }
  });
})();
