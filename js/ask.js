/* ==========================================================================
   三浦 海の学校 — 相談シート（2026-11）
   --------------------------------------------------------------------------
   どのページからでも、コース・日にち・人数を「えらぶだけ」で相談の文面ができ、
   そのまま LINE（文面をコピーして友だち追加のページを開く）か、
   フォーム（/contact/ に入力済みで進む）へ送れる。

   ・js/main.js が、押されそうになったとき（または少しあと）にこのファイルを読み込む。
     読み込めなかったときは、ボタンは今までどおり LINE を開く（main.js 側で面倒を見る）。
   ・ここに料金や事実は書かない。書くのはコース名・日にち・人数だけ。
     コースの value（cat）は contact/send_mail.php が受け取る値と同じ。変えない。
   ・見た目もこのファイルの中（STYLE）。ほかの CSS は要らない。
   ・LINE の行き先は友だち追加（lin.ee）のまま。合言葉は使わない。
   ========================================================================== */
(function () {
  'use strict';
  if (window.UmiAsk) return;

  var LINE_URL = 'https://lin.ee/kK3d5p2';
  var QR_SRC = '/image/optimized/line-qr.webp';

  var COURSES = [
    { k: 'trial', title: '体験ダイビングの空きを、聞いてみる。',   label: '体験ダイビング',   sub: 'ライセンス不要', cat: '体験ダイビング',       msg: '体験ダイビングの空き日程を知りたいです。' },
    { k: 'license', title: 'ライセンス講習の日程を、相談する。', label: 'ライセンス講習',   sub: 'オープンウォーター',       cat: 'OWDライセンス',        msg: 'ライセンス講習（オープンウォーター）の日程を相談したいです。' },
    { k: 'fun', title: 'ファンダイビングの空きを、聞いてみる。',     label: 'ファンダイビング', sub: 'Cカードをお持ちの方',      cat: 'ファンダイビング',     msg: 'ファンダイビングの空き日程を知りたいです。' },
    { k: 'refresh', title: 'リフレッシュの日程を、相談する。', label: 'リフレッシュ',     sub: 'ブランクがある方',         cat: 'リフレッシュ',         msg: 'しばらく潜っていないので、リフレッシュダイビングを相談したいです。' },
    { k: 'step', title: 'アドバンス・SPの日程を、相談する。',    label: 'アドバンス・SP',   sub: 'ステップアップ',     cat: 'AOWライセンス',        msg: 'アドバンス・スペシャルティ講習の日程を相談したいです。' },
    { k: 'snorkel', title: 'スノーケリングの空きを、聞いてみる。', label: 'スノーケリング',   sub: '親子でも',   cat: 'マリンアクティビティ', msg: 'スノーケリングの空き日程を知りたいです。' },
    { k: 'unsure', title: 'どのコースが合うか、聞いてみる。',  label: 'まだ迷っている',   sub: '相談して決めたい',         cat: 'その他',               msg: 'どのコースが合うか、相談したいです。' }
  ];
  var PEOPLE = ['1名', '2名', '3名', '4名', '5名以上'];
  var WD = ['日', '月', '火', '水', '木', '金', '土'];

  /* ---------- 日本時間の「今日」と、次の週末 ---------- */
  function jstToday() {
    var n = new Date();
    var j = new Date(n.getTime() + n.getTimezoneOffset() * 60000 + 9 * 3600000);
    return new Date(j.getFullYear(), j.getMonth(), j.getDate());
  }
  function addDays(d, n) { return new Date(d.getFullYear(), d.getMonth(), d.getDate() + n); }
  function pad(n) { return (n < 10 ? '0' : '') + n; }
  function iso(d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); }
  function md(d) { return (d.getMonth() + 1) + '/' + d.getDate(); }
  function mdw(d) { return md(d) + '(' + WD[d.getDay()] + ')'; }
  function weekendText(sat) {
    var sun = addDays(sat, 1);
    /* 月をまたぐ週末は「11/28(土)・11/29(日)」ではなく「11/28(土)・29(日)」。月が変わるときだけ月を書く */
    return mdw(sat) + '・' + (sun.getMonth() === sat.getMonth() ? sun.getDate() + '(日)' : mdw(sun));
  }
  function weekendShort(sat) {
    var sun = addDays(sat, 1);
    return md(sat) + '・' + (sun.getMonth() === sat.getMonth() ? sun.getDate() : md(sun));
  }

  var today = jstToday();
  var dow = today.getDay();
  var toSat = (6 - dow + 7) % 7; if (toSat === 0) toSat = 7;          /* 今日が土曜なら、次の土曜 */
  var sat1 = addDays(today, toSat), sat2 = addDays(sat1, 7);
  var weekday = dow >= 1 && dow <= 5;
  var DATES = [
    { k: 'w1', label: weekday ? '今週末' : '次の週末',   sub: weekendShort(sat1), text: weekendText(sat1), iso: iso(sat1) },
    { k: 'w2', label: weekday ? '来週末' : 'その次の週末', sub: weekendShort(sat2), text: weekendText(sat2), iso: iso(sat2) },
    { k: 'wd', label: '平日',       sub: '月〜金', text: '平日', iso: '' }
  ];

  /* ---------- 見た目 ---------- */
  var STYLE = [
    '.uask{--ink:#0B2A3A;--teal:#0F766E;--line:#00873C;--line-h:#00722F;--coral:#D12F6A;--soft:#F0F7FA;--bd:#D9E6EC;',
    /* overflow は clip（スクロールできない箱）。hidden だと、iPhone の Safari が開く瞬間にこの箱をスクロールさせてしまう（下の settle() の説明） */
    'position:fixed;inset:0;margin:0;padding:0;border:0;width:100%;max-width:none;height:100%;max-height:none;background:transparent;color:var(--ink);overflow:hidden;overflow:clip;',
    'font-family:var(--ff-body,"Zen Maru Gothic","Hiragino Maru Gothic ProN",sans-serif);-webkit-tap-highlight-color:transparent}',
    '.uask::backdrop{background:rgba(4,38,58,.56);backdrop-filter:blur(3px);-webkit-backdrop-filter:blur(3px)}',
    '.uask[open]{display:flex;align-items:flex-end;justify-content:center}',
    '.uask__sheet{position:relative;display:flex;flex-direction:column;width:100%;min-width:0;max-width:560px;max-height:min(94dvh,860px);background:#fff;border-radius:26px 26px 0 0;',
    'box-shadow:0 -18px 60px rgba(4,38,58,.35);overflow:hidden;overflow:clip;transform:translateY(var(--drag,0px));will-change:transform}',
    '.uask[open] .uask__sheet{animation:uask-up .46s cubic-bezier(.2,.85,.25,1) both}',
    '.uask[open]::backdrop{animation:uask-fade .3s ease both}',
    '.uask.is-closing .uask__sheet{animation:uask-down .26s cubic-bezier(.5,0,.8,.4) both}',
    '.uask.is-closing::backdrop{animation:uask-fade .26s ease reverse both}',
    '@keyframes uask-up{from{transform:translateY(102%)}to{transform:translateY(0)}}',
    '@keyframes uask-down{from{transform:translateY(var(--drag,0px))}to{transform:translateY(104%)}}',
    '@keyframes uask-fade{from{opacity:0}to{opacity:1}}',
    /* 頭：水面の下の色。下の縁が波 */
    '.uask__head{position:relative;flex:none;padding:1.5rem 3.6rem 2.15rem 1.35rem;color:#fff;background:linear-gradient(160deg,#0F849E 0%,#095574 58%,#063A57 100%);touch-action:none;user-select:none;-webkit-user-select:none}',
    '.uask__head::after{content:"";position:absolute;left:0;right:0;bottom:-1px;height:18px;background:#fff;',
    '-webkit-mask:url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 120 18\' preserveAspectRatio=\'none\'%3E%3Cpath d=\'M0 9c10-8 20-8 30 0s20 8 30 0 20-8 30 0 20 8 30 0v9H0z\'/%3E%3C/svg%3E") repeat-x 0 0/120px 18px;',
    'mask:url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 120 18\' preserveAspectRatio=\'none\'%3E%3Cpath d=\'M0 9c10-8 20-8 30 0s20 8 30 0 20-8 30 0 20 8 30 0v9H0z\'/%3E%3C/svg%3E") repeat-x 0 0/120px 18px}',
    '.uask__grab{position:absolute;left:50%;top:.5rem;width:44px;height:5px;margin-left:-22px;border-radius:5px;background:rgba(255,255,255,.45)}',
    '.uask__eyebrow{display:inline-flex;align-items:center;gap:.4rem;margin:0 0 .35rem;font-size:.75rem;font-weight:700;letter-spacing:.08em;color:#BFEFF0}',
    '.uask__eyebrow i{width:7px;height:7px;border-radius:50%;background:#FFE66D;box-shadow:0 0 0 4px rgba(255,230,109,.22)}',
    '.uask__title{margin:0;font-size:1.32rem;line-height:1.4;font-weight:700;letter-spacing:.01em;color:#fff}',
    '.uask__lead{margin:.3rem 0 0;font-size:.86rem;line-height:1.7;color:rgba(255,255,255,.9)}',
    '.uask__close{position:absolute;right:.55rem;top:.75rem;width:46px;height:46px;border:0;border-radius:50%;background:rgba(255,255,255,.14);color:#fff;font-size:1.5rem;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;touch-action:manipulation}',
    '.uask__close:hover{background:rgba(255,255,255,.26)}',
    '.uask [hidden]{display:none!important}',
    '.uask__sheet:focus,.uask__done:focus{outline:none}',
    '.uask__btn:focus-visible,.uask__close:focus-visible,.uask__link:focus-visible,.uask__picked button:focus-visible{outline:3px solid #FFB020;outline-offset:3px}',
    /* ページ側の決まり（page.css・home.css の「[tabindex]:focus-visible に枠線と角10px」）が、シートの中に効かないようにする。
       シートと「コピーしました」の面は、開いたときにフォーカスを置く場所なので、枠線は出さない。丸いボタンは丸いまま */
    '.uask.uask .uask__sheet:focus-visible{outline:0;border-radius:26px 26px 0 0}',
    '.uask.uask .uask__done:focus-visible{outline:0;border-radius:0}',
    '.uask.uask .uask__close:focus-visible{border-radius:50%}',
    '.uask.uask .uask__btn:focus-visible{border-radius:999px}',
    '.uask__cols{display:flex;flex-direction:column;flex:1 1 auto;min-height:0}',
    /* 本文 */
    '.uask__body{flex:1 1 auto;min-height:0;overflow-y:auto;overscroll-behavior:contain;-webkit-overflow-scrolling:touch;padding:.4rem 1.2rem 1rem}',
    '.uask__q{border:0;margin:1.15rem 0 0;padding:0;min-width:0}',
    '.uask__q legend{display:flex;align-items:center;gap:.55rem;width:100%;padding:0;margin:0 0 .6rem;font-size:1rem;font-weight:700;color:var(--ink)}',
    '.uask__q legend b{flex:none;width:1.65rem;height:1.65rem;border-radius:50%;background:var(--ink);color:#fff;font-family:var(--ff-accent,Montserrat,sans-serif);font-size:.82rem;display:inline-flex;align-items:center;justify-content:center}',
    '.uask__q legend small{margin-left:auto;font-size:.75rem;font-weight:700;color:#5B7280;background:var(--soft);border-radius:999px;padding:.2rem .65rem}',
    '.uask__chips{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.5rem}',
    '.uask__chips--row{grid-template-columns:repeat(4,minmax(0,1fr)) minmax(0,1.5fr);gap:.4rem}',
    '.uask-chip{position:relative;display:block;cursor:pointer}',
    '.uask-chip input{position:absolute;opacity:0;width:1px;height:1px;pointer-events:none}',
    '.uask-chip>span{display:flex;flex-direction:column;justify-content:center;min-height:52px;height:100%;padding:.5rem .8rem;border:1.5px solid var(--bd);border-radius:15px;background:#fff;',
    'font-size:.95rem;font-weight:700;line-height:1.35;color:var(--ink);transition:background .18s ease,border-color .18s ease,color .18s ease,transform .18s ease,box-shadow .18s ease}',
    '.uask-chip>span small{display:block;margin-top:.1rem;font-size:.75rem;font-weight:700;color:#5B7280;transition:color .18s ease}',
    '.uask-chip:hover>span{border-color:#9CC4CF}',
    '.uask-chip input:focus-visible+span{outline:3px solid #FFB020;outline-offset:2px}',
    '.uask-chip input:checked+span{background:var(--teal);border-color:var(--teal);color:#fff;box-shadow:0 8px 18px rgba(15,118,110,.26);animation:uask-pop .32s cubic-bezier(.3,1.5,.5,1)}',
    '.uask-chip input:checked+span small{color:rgba(255,255,255,.88)}',
    '@keyframes uask-pop{0%{transform:scale(.96)}60%{transform:scale(1.03)}100%{transform:scale(1)}}',
    '.uask__chips--row .uask-chip>span{align-items:center;text-align:center;padding:.5rem .2rem;white-space:nowrap}',
    '.uask__chips--dates{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:.5rem}',
    '.uask__chips--dates .uask-chip>span{align-items:center;text-align:center;padding:.45rem .3rem}',
    '.uask__chips--dates .uask-chip--wide{grid-column:span 3}',
    '.uask__pick{display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin-top:.5rem}',
    '.uask-date{position:relative;display:flex;align-items:center;justify-content:center;gap:.45rem;min-height:52px;padding:.4rem .6rem;border:1.5px dashed #A9C6D0;border-radius:15px;background:var(--soft);font-size:.92rem;font-weight:700;color:var(--ink);cursor:pointer;transition:background .18s ease,border-color .18s ease,color .18s ease}',
    '.uask-date svg{width:1.05rem;height:1.05rem;fill:currentColor;flex:none}',
    '.uask-date input{position:absolute;inset:0;width:100%;height:100%;opacity:0;cursor:pointer;border:0;padding:0;margin:0;font-size:16px}',
    '.uask-date.is-set{border-style:solid;border-color:var(--teal);background:var(--teal);color:#fff;box-shadow:0 8px 18px rgba(15,118,110,.26)}',
    '.uask-date:focus-within{outline:3px solid #FFB020;outline-offset:2px}',
    /* コースが決まっているときの、たたんだ表示 */
    '.uask__picked{display:flex;align-items:center;gap:.6rem;padding:.7rem .9rem;border-radius:15px;background:var(--soft);font-weight:700;font-size:.98rem}',
    '.uask__picked svg{flex:none;width:1.25rem;height:1.25rem;fill:var(--teal)}',
    '.uask__picked button{margin-left:auto;flex:none;border:0;background:none;padding:.5rem .2rem;font:inherit;font-size:.82rem;font-weight:700;color:var(--teal);text-decoration:underline;text-underline-offset:3px;cursor:pointer}',
    /* ひとこと */
    '.uask__note{margin-top:1rem}',
    '.uask__note label{display:flex;align-items:center;gap:.5rem;font-size:.86rem;font-weight:700;color:#3F5866;margin-bottom:.35rem}',
    '.uask__note label small{font-size:.75rem;color:#5B7280;background:var(--soft);border-radius:999px;padding:.15rem .6rem}',
    '.uask__note input{width:100%;min-height:48px;padding:.6rem .9rem;border:1.5px solid var(--bd);border-radius:14px;background:#fff;font:inherit;font-size:16px;color:var(--ink)}',
    '.uask__note input:focus{outline:0;border-color:var(--teal);box-shadow:0 0 0 4px rgba(15,118,110,.14)}',
    /* 送る文面（トークのふきだし） */
    '.uask__talk{margin-top:1rem;padding:.9rem .9rem 1rem;border-radius:18px;background:#E6F0F4;background-image:radial-gradient(rgba(255,255,255,.7) 1px,transparent 1.5px);background-size:14px 14px}',
    '.uask__talk p{margin:0 0 .5rem;font-size:.75rem;font-weight:700;letter-spacing:.06em;color:#4A6472}',
    '.uask__bubble{position:relative;margin-left:auto;max-width:92%;width:fit-content;padding:.7rem .95rem;border-radius:18px 18px 4px 18px;background:#C8F2CD;color:#0B2A3A;font-size:.95rem;line-height:1.7;font-weight:500;white-space:pre-wrap;word-break:break-word;box-shadow:0 2px 0 rgba(0,90,40,.08);transition:background .25s ease}',
    '.uask__bubble.is-new{animation:uask-bubble .45s cubic-bezier(.3,1.4,.5,1)}',
    '@keyframes uask-bubble{0%{transform:scale(.97);background:#E9FBEB}100%{transform:scale(1)}}',
    /* 足：送る */
    '.uask__foot{flex:none;padding:.8rem 1.2rem calc(.9rem + env(safe-area-inset-bottom));border-top:1px solid #E5EDF2;background:rgba(255,255,255,.96);box-shadow:0 -10px 24px rgba(11,42,58,.06)}',
    '.uask__send{display:grid;gap:.55rem}',
    '.uask__btn{display:flex;align-items:center;justify-content:center;gap:.6rem;min-height:56px;padding:.55rem 1.2rem;border-radius:999px;border:0;font:inherit;font-weight:700;font-size:1.04rem;line-height:1.25;text-decoration:none;cursor:pointer;transition:transform .2s ease,background .2s ease,box-shadow .2s ease;touch-action:manipulation}',
    '.uask__btn small{display:block;font-size:.75rem;font-weight:700;opacity:.92}',
    '.uask__btn:active{transform:scale(.98)}',
    '.uask__btn--line{background:var(--line);color:#fff;box-shadow:0 10px 26px rgba(0,110,50,.3)}',
    '.uask__btn--line:hover{background:var(--line-h);color:#fff}',
    '.uask__btn--line .m{flex:none;width:1.9rem;height:1.9rem;border-radius:.55rem;background:#fff;display:inline-flex;align-items:center;justify-content:center}',
    '.uask__btn--line .m svg{width:1.5rem;height:1.5rem;fill:#06C755}',
    '.uask__btn--form{min-height:50px;background:#fff;color:var(--ink);border:1.5px solid #B9CED7;font-size:.98rem}',
    '.uask__btn--form:hover{border-color:var(--ink);color:var(--ink)}',
    '.uask__btn--form svg{width:1.1rem;height:1.1rem;fill:currentColor}',
    '.uask__fine{margin:.6rem 0 0;text-align:center;font-size:.75rem;line-height:1.6;color:#5B7280}',
    /* 送ったあと（LINE） */
    '.uask__done{display:none;flex:1 1 auto;min-height:0;overflow-y:auto;overscroll-behavior:contain;padding:1.1rem 1.25rem calc(1.2rem + env(safe-area-inset-bottom))}',
    '.uask.is-done .uask__body,.uask.is-done .uask__foot{display:none}',
    '.uask.is-done .uask__done{display:block}',
    '.uask__ok{display:flex;align-items:center;gap:.7rem;margin:0 0 .2rem;font-size:1.12rem;font-weight:700;color:var(--ink)}',
    '.uask__ok i{flex:none;width:2.2rem;height:2.2rem;border-radius:50%;background:var(--line);display:inline-flex;align-items:center;justify-content:center;animation:uask-pop .5s cubic-bezier(.3,1.6,.5,1)}',
    '.uask__ok i svg{width:1.25rem;height:1.25rem;fill:none;stroke:#fff;stroke-width:3;stroke-linecap:round;stroke-linejoin:round}',
    '.uask__ok.is-ng i{background:#B45309}',
    '.uask__done .uask__bubble{-webkit-user-select:all;user-select:all}',
    '.uask__steps{list-style:none;margin:.9rem 0 0;padding:0;display:grid;gap:.5rem;counter-reset:s}',
    '.uask__steps li{counter-increment:s;display:flex;align-items:center;gap:.75rem;padding:.7rem .9rem;border-radius:15px;background:var(--soft);font-size:.95rem;font-weight:700;line-height:1.5}',
    '.uask__steps li::before{content:counter(s);flex:none;width:1.7rem;height:1.7rem;border-radius:50%;background:#fff;color:var(--teal);font-family:var(--ff-accent,Montserrat,sans-serif);font-size:.85rem;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 0 0 1.5px #BFD9DF inset}',
    '.uask__steps li span{font-weight:500;color:#3F5866}',
    '.uask__again{display:grid;gap:.55rem;margin-top:1rem}',
    '.uask__link{border:0;background:none;padding:.7rem;font:inherit;font-size:.9rem;font-weight:700;color:var(--teal);text-decoration:underline;text-underline-offset:3px;cursor:pointer}',
    /* パソコン：まん中に出す。右にQR */
    '.uask__qr,.uask__pc{display:none}',
    '@media (min-width:860px) and (hover:hover){',
    '.uask[open]{align-items:center}',
    '.uask__sheet{max-width:920px;border-radius:26px;max-height:min(90dvh,820px)}',
    '.uask.uask .uask__sheet:focus-visible{border-radius:26px}',
    '.uask[open] .uask__sheet{animation:uask-in .36s cubic-bezier(.2,.85,.25,1) both}',
    '.uask.is-closing .uask__sheet{animation:uask-out .2s ease both}',
    '@keyframes uask-in{from{opacity:0;transform:translateY(18px) scale(.98)}to{opacity:1;transform:none}}',
    '@keyframes uask-out{to{opacity:0;transform:translateY(10px) scale(.985)}}',
    '.uask__grab{display:none}',
    '.uask__head{padding:1.6rem 4rem 2.3rem 2rem;touch-action:auto}',
    '.uask__title{font-size:1.5rem}',
    '.uask__cols{display:grid;grid-template-columns:minmax(0,1.15fr) minmax(0,.85fr);min-height:0;flex:1 1 auto}',
    '.uask__body{padding:.4rem 1.6rem 1.4rem 2rem}',
    '.uask__foot{border-top:0;border-left:1px solid #E5EDF2;box-shadow:none;padding:1.3rem 1.8rem 1.5rem 1.6rem;display:flex;flex-direction:column;overflow-y:auto}',
    '.uask__talk--foot{margin-top:0}',
    '.uask__send{margin-top:1rem}',
    '.uask__btn--form{order:-1;min-height:56px;background:var(--coral);border-color:var(--coral);color:#fff;box-shadow:0 10px 24px rgba(209,47,106,.25);font-size:1.04rem}',
    '.uask__btn--form:hover{background:#B8245A;border-color:#B8245A;color:#fff}',
    '.uask__btn--line{background:#fff;color:var(--line);box-shadow:none;border:1.5px solid #9AD3AE;min-height:50px;font-size:.98rem}',
    '.uask__btn--line:hover{background:#F1FBF4;color:var(--line-h)}',
    '.uask__btn--line .m{background:#06C755}.uask__btn--line .m svg{fill:#fff}',
    '.uask__qr{display:flex;align-items:center;gap:.9rem;margin-top:.9rem;padding:.8rem;border-radius:16px;background:var(--soft);font-size:.8rem;line-height:1.6;color:#3F5866}',
    '.uask__qr img{flex:none;width:92px;height:92px;border-radius:10px;background:#fff}',
    '.uask__qr b{display:block;color:var(--ink);font-size:.88rem}',
    '.uask.is-done .uask__cols{display:none}',
    '.uask__done{padding:1.4rem 2rem 1.8rem}',
    '.uask__steps--sp{display:none}',
    '.uask__pc{display:grid;grid-template-columns:minmax(0,.9fr) minmax(0,1.1fr);gap:.9rem;align-items:start;margin-top:.9rem}',
    '.uask__pc .uask__qr{margin-top:0;height:100%}',
    '.uask__pc .uask__steps{margin-top:0}',
    '.uask__again{grid-template-columns:1fr 1fr;align-items:center}',
    '}',
    '@media (max-width:859.98px){.uask__talk--foot{display:none}}',
    '@media (min-width:860px) and (hover:hover){.uask__talk--body{display:none}}',
    '@media (max-width:359px){.uask__chips--row .uask-chip>span{font-size:.84rem}.uask__chips--dates .uask-chip>span{font-size:.84rem;padding-left:.15rem;padding-right:.15rem}.uask-chip>span{font-size:.9rem;padding-left:.6rem;padding-right:.5rem}',
    '.uask__title{font-size:1.16rem}.uask__q legend{font-size:.94rem}.uask__q legend small{display:none}.uask__body{padding-left:1rem;padding-right:1rem}.uask__foot{padding-left:1rem;padding-right:1rem}.uask-date{font-size:.86rem}}',
    /* 背の低い画面：頭と足を小さくして、えらぶ所を広く */
    '@media (max-height:740px) and (max-width:859.98px){.uask__lead,.uask__fine{display:none}.uask__head{padding-top:1.3rem;padding-bottom:1.75rem}.uask__title{font-size:1.2rem}.uask__btn{min-height:52px}.uask__btn--form{min-height:46px}}',
    '@media (max-height:620px) and (max-width:859.98px){.uask__eyebrow{display:none}.uask__head{padding-top:1.1rem;padding-bottom:1.5rem}.uask__title{font-size:1.08rem}.uask__send{grid-template-columns:1.25fr 1fr}.uask__btn{min-height:50px;padding:.4rem .5rem;font-size:.94rem;gap:.4rem}.uask__btn--form{min-height:50px}.uask__btn small{display:none}.uask__btn--line .m{width:1.6rem;height:1.6rem}.uask__btn--line .m svg{width:1.25rem;height:1.25rem}.uask__foot{padding-top:.6rem}}',
    '@media (prefers-reduced-motion:reduce){.uask[open] .uask__sheet,.uask.is-closing .uask__sheet,.uask[open]::backdrop,.uask.is-closing::backdrop,.uask-chip input:checked+span,.uask__bubble.is-new,.uask__ok i{animation:none}}',
    '@media print{.uask{display:none!important}}'
  ].join('');

  var ICON_LINE = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/></svg>';
  var ICON_MAIL = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 5h18a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zm1 2.2V17h16V7.2l-8 5.3-8-5.3zM5.3 7l6.7 4.4L18.7 7H5.3z"/></svg>';
  var ICON_CAL = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V2h-2v2H9V2H7zm-2 7h14v11H5V9z"/></svg>';
  var ICON_CHECK = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>';
  var ICON_BANG = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 6v8M12 18v.5"/></svg>';
  var ICON_OK = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm-1.2 14.2-4-4 1.4-1.4 2.6 2.6 5.4-5.4 1.4 1.4-6.8 6.8z"/></svg>';

  function esc(s) { return String(s).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); }
  function el(html) { var t = document.createElement('template'); t.innerHTML = html.trim(); return t.content.firstChild; }

  var dlg = null, state = null, lastFocus = null, built = false;
  var $ = function (s) { return dlg.querySelector(s); };
  var $$ = function (s) { return Array.prototype.slice.call(dlg.querySelectorAll(s)); };

  function build() {
    if (built) return;
    built = true;
    var st = document.createElement('style');
    st.setAttribute('data-uask', '');
    st.textContent = STYLE;
    document.head.appendChild(st);

    var courseChips = COURSES.map(function (c) {
      return '<label class="uask-chip"><input type="radio" name="uask-course" value="' + c.k + '"><span>' + esc(c.label) + '<small>' + esc(c.sub) + '</small></span></label>';
    }).join('');
    var dateChips = DATES.map(function (d) {
      return '<label class="uask-chip"><input type="checkbox" name="uask-date" value="' + d.k + '"><span>' + esc(d.label) + '<small>' + esc(d.sub) + '</small></span></label>';
    }).join('');
    var peopleChips = PEOPLE.map(function (p) {
      return '<label class="uask-chip"><input type="radio" name="uask-people" value="' + p + '"><span>' + esc(p) + '</span></label>';
    }).join('');
    var talk = function (mod) {
      return '<div class="uask__talk uask__talk--' + mod + '"><p>送る文面</p><div class="uask__bubble" data-uask-bubble' + (mod === 'body' ? ' aria-live="polite"' : ' aria-hidden="true"') + '></div></div>';
    };

    dlg = el(
      '<dialog class="uask" aria-labelledby="uask-title">' +
        '<div class="uask__sheet">' +
          '<div class="uask__head" data-uask-drag>' +
            '<span class="uask__grab" aria-hidden="true"></span>' +
            '<p class="uask__eyebrow"><i aria-hidden="true"></i>えらぶだけ・30秒</p>' +
            '<h2 class="uask__title" id="uask-title">空き日程を、聞いてみる。</h2>' +
            '<p class="uask__lead">えらぶと、送る文面ができます。</p>' +
            '<button type="button" class="uask__close" data-uask-close aria-label="閉じる">×</button>' +
          '</div>' +
          '<div class="uask__cols">' +
            '<div class="uask__body">' +
              '<fieldset class="uask__q" data-uask-course>' +
                '<legend><b>1</b>どのコースですか？</legend>' +
                '<div class="uask__picked" data-uask-picked hidden>' + ICON_OK + '<span data-uask-picked-label></span><button type="button" data-uask-change>変える</button></div>' +
                '<div class="uask__chips" data-uask-course-chips>' + courseChips + '</div>' +
              '</fieldset>' +
              '<fieldset class="uask__q">' +
                '<legend><b>2</b>いつごろがいいですか？<small>いくつでも</small></legend>' +
                '<div class="uask__chips--dates">' + dateChips + '</div>' +
                '<div class="uask__pick">' +
                  '<label class="uask-date" data-uask-pick>' + ICON_CAL + '<span data-uask-pick-label>日にちを選ぶ</span><input type="date" name="uask-day" aria-label="希望日を選ぶ"></label>' +
                  '<label class="uask-chip"><input type="checkbox" name="uask-date" value="none"><span style="align-items:center;text-align:center">まだ未定</span></label>' +
                '</div>' +
              '</fieldset>' +
              '<fieldset class="uask__q">' +
                '<legend><b>3</b>何名ですか？</legend>' +
                '<div class="uask__chips uask__chips--row">' + peopleChips + '</div>' +
              '</fieldset>' +
              '<div class="uask__note"><label for="uask-note">ひとこと<small>なくても大丈夫</small></label>' +
                '<input type="text" id="uask-note" maxlength="120" autocomplete="off" enterkeyhint="done" placeholder="例：泳ぎが苦手です／ブランクが5年あります"></div>' +
              talk('body') +
            '</div>' +
            '<div class="uask__foot">' +
              talk('foot') +
              '<div class="uask__send">' +
                '<a class="uask__btn uask__btn--line" data-uask-line href="' + LINE_URL + '" target="_blank" rel="noopener noreferrer"><span class="m">' + ICON_LINE + '</span><span>LINEで送る<small>文面をコピーして、LINEを開きます</small></span></a>' +
                '<a class="uask__btn uask__btn--form" data-uask-form href="/contact/">' + ICON_MAIL + '<span>フォームで送る</span></a>' +
              '</div>' +
              '<div class="uask__qr"><img src="' + QR_SRC + '" alt="三浦 海の学校 公式LINEの友だち追加QRコード" width="92" height="92" loading="lazy" decoding="async"><span><b>スマホのLINEで読み取り</b>友だち追加のあと、上の文面を送ってください。</span></div>' +
              '<p class="uask__fine">質問だけでも大丈夫です。キャンセルは前日17時まで無料。</p>' +
            '</div>' +
          '</div>' +
          '<div class="uask__done" data-uask-done tabindex="-1">' +
            '<p class="uask__ok" data-uask-ok><i aria-hidden="true">' + ICON_CHECK + '</i><span data-uask-ok-text>文面をコピーしました</span></p>' +
            '<div class="uask__talk"><p data-uask-done-label>コピーした文面</p><div class="uask__bubble" data-uask-bubble></div></div>' +
            '<ol class="uask__steps uask__steps--sp">' +
              '<li>LINEで「追加」<span>（友だち追加）</span></li>' +
              '<li>トークの入力らんを長押し →「ペースト」</li>' +
              '<li>送信<span>（お返事は、通常1営業日以内）</span></li>' +
            '</ol>' +
            '<div class="uask__pc">' +
              '<div class="uask__qr"><img src="' + QR_SRC + '" alt="三浦 海の学校 公式LINEの友だち追加QRコード" width="92" height="92" loading="lazy" decoding="async"><span><b>スマホのLINEで読み取り</b>ひらいたページにも、同じQRコードが出ます。</span></div>' +
              '<ol class="uask__steps">' +
                '<li>QRコードを読み取って「追加」<span>（友だち追加）</span></li>' +
                '<li>トークで、上の文面を送る<span>（パソコンのLINEなら、貼り付けて送信）</span></li>' +
              '</ol>' +
            '</div>' +
            '<div class="uask__again">' +
              '<a class="uask__btn uask__btn--line" data-uask-line2 href="' + LINE_URL + '" target="_blank" rel="noopener noreferrer"><span class="m">' + ICON_LINE + '</span><span>もう一度、LINEを開く</span></a>' +
              '<button type="button" class="uask__link" data-uask-back>文面を直す</button>' +
            '</div>' +
          '</div>' +
        '</div>' +
      '</dialog>'
    );
    document.body.appendChild(dlg);

    /* ---------- 動き ---------- */
    dlg.addEventListener('change', function (e) {
      var t = e.target;
      if (t.name === 'uask-course') { state.course = t.value; state.baseMsg = ''; state.title = ''; collapseCourse(false); setTitle(); }
      if (t.name === 'uask-people') state.people = t.value;
      if (t.name === 'uask-date') {
        if (t.value === 'none' && t.checked) {
          $$('input[name="uask-date"]').forEach(function (i) { if (i !== t) i.checked = false; });
          setDay('');
        } else if (t.checked) {
          var none = $('input[name="uask-date"][value="none"]'); if (none) none.checked = false;
        }
      }
      if (t.name === 'uask-day') {
        setDay(t.value);
        if (t.value) { var n2 = $('input[name="uask-date"][value="none"]'); if (n2) n2.checked = false; }
      }
      render(true);
    });
    $('#uask-note').addEventListener('input', function () { render(false); });
    /* 日にちの欄は見えない入力らんを重ねてある。押したら、その場でカレンダーを出す */
    $('input[name="uask-day"]').addEventListener('click', function (e) { try { if (e.target.showPicker) e.target.showPicker(); } catch (x) {} });
    $('#uask-note').addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); e.target.blur(); } });

    /* 人数・コースは、もう一度押すと外せる（ラジオは外せないので） */
    dlg.addEventListener('click', function (e) {
      var lab = e.target.closest && e.target.closest('.uask-chip');
      if (lab) {
        var inp = lab.querySelector('input[type="radio"]');
        if (inp && inp.checked && lab.getAttribute('data-was') === '1') {
          e.preventDefault();
          inp.checked = false;
          if (inp.name === 'uask-people') state.people = '';
          if (inp.name === 'uask-course') { state.course = ''; state.baseMsg = ''; state.title = ''; setTitle(); }
          render(true);
        }
        $$('.uask-chip').forEach(function (l) { var i = l.querySelector('input[type="radio"]'); if (i) l.setAttribute('data-was', i.checked ? '1' : '0'); });
        return;
      }
      if (e.target.closest('[data-uask-close]')) { close(); return; }
      if (e.target.closest('[data-uask-change]')) { collapseCourse(false, true); return; }
      if (e.target.closest('[data-uask-back]')) { dlg.classList.remove('is-done'); var b = $('.uask__body'); if (b) b.scrollTop = 0; return; }
      if (e.target === dlg) close();            /* 外側（暗いところ）を押したら閉じる */
    });
    /* クリックの直前の状態を覚える（上の「もう一度押すと外せる」のため） */
    dlg.addEventListener('pointerdown', function (e) {
      var lab = e.target.closest && e.target.closest('.uask-chip');
      if (!lab) return;
      var i = lab.querySelector('input[type="radio"]');
      if (i) lab.setAttribute('data-was', i.checked ? '1' : '0');
    });

    dlg.addEventListener('cancel', function (e) { e.preventDefault(); close(); });   /* Esc */

    /* LINE：文面をコピーして、そのままリンクを開く（リンク自体は止めない） */
    function onLine() {
      var text = message();
      copy(text).then(function () { done(true); }, function () { done(false); });
    }
    $('[data-uask-line]').addEventListener('click', onLine);
    $('[data-uask-line2]').addEventListener('click', function () { copy(message()).catch(function () {}); });

    /* 頭をつまんで下へ引くと閉じる（スマホ） */
    (function drag() {
      var head = $('[data-uask-drag]'), sheet = $('.uask__sheet');
      var y0 = 0, dy = 0, on = false;
      head.addEventListener('pointerdown', function (e) {
        if (e.target.closest('button') || window.matchMedia('(min-width:860px) and (hover:hover)').matches) return;
        on = true; y0 = e.clientY; dy = 0; sheet.style.transition = 'none';
        try { head.setPointerCapture(e.pointerId); } catch (x) {}
      });
      head.addEventListener('pointermove', function (e) {
        if (!on) return;
        dy = Math.max(0, e.clientY - y0);
        sheet.style.setProperty('--drag', dy + 'px');
      });
      function end() {
        if (!on) return;
        on = false; sheet.style.transition = '';
        if (dy > 90) { close(); }
        else { sheet.style.transition = 'transform .25s ease'; sheet.style.setProperty('--drag', '0px'); setTimeout(function () { sheet.style.transition = ''; }, 260); }
      }
      head.addEventListener('pointerup', end);
      head.addEventListener('pointercancel', end);
    })();

    window.addEventListener('pagehide', function () { if (dlg.open) { dlg.classList.remove('is-closing'); dlg.close(); unlock(); } });

    /* 外わくとシートは、スクロールしてはいけない箱。動いたら、すぐ 0 に戻す（settle() の説明） */
    dlg.addEventListener('scroll', settle, { passive: true });
    $('.uask__sheet').addEventListener('scroll', settle, { passive: true });
    $('.uask__sheet').addEventListener('animationend', settle);
  }

  /* iPhone の Safari は、開く瞬間（シートがまだ画面の下にある間）に、フォーカスした物を見せようとして
     <dialog> そのものを下へスクロールさせ、動きが終わっても戻さない。すると、シートが画面の上へはみ出して、
     下のふちしか見えなくなる（2026-11-14 に本番で起きた）。
     いまの Safari では CSS の overflow:clip でスクロールそのものができない。ここは、clip を知らない古い Safari のための戻し。 */
  function settle() {
    if (!dlg) return;
    if (dlg.scrollTop) dlg.scrollTop = 0;
    if (dlg.scrollLeft) dlg.scrollLeft = 0;
    var s = $('.uask__sheet');
    if (s) { if (s.scrollTop) s.scrollTop = 0; if (s.scrollLeft) s.scrollLeft = 0; }
  }

  function setDay(v) {
    state.day = v || '';
    var inp = $('input[name="uask-day"]'), lab = $('[data-uask-pick]'), txt = $('[data-uask-pick-label]');
    if (inp.value !== state.day) inp.value = state.day;
    if (state.day) {
      var p = state.day.split('-'), d = new Date(+p[0], +p[1] - 1, +p[2]);
      txt.textContent = mdw(d);
      lab.classList.add('is-set');
    } else {
      txt.textContent = '日にちを選ぶ';
      lab.classList.remove('is-set');
    }
  }

  function setTitle() {
    var c = courseOf(state.course);
    $('#uask-title').textContent = state.title || (c ? c.title : '空き日程を、聞いてみる。');
  }

  function courseOf(k) { for (var i = 0; i < COURSES.length; i++) if (COURSES[i].k === k) return COURSES[i]; return null; }

  /* コースがページから決まっているときは、1行にたたむ（「変える」で開く） */
  function collapseCourse(yes, focus) {
    var c = courseOf(state.course);
    var picked = $('[data-uask-picked]'), chips = $('[data-uask-course-chips]');
    if (yes && c) {
      $('[data-uask-picked-label]').textContent = c.label;
      picked.hidden = false; chips.hidden = true;
    } else {
      picked.hidden = true; chips.hidden = false;
      if (focus) { var i = chips.querySelector('input:checked') || chips.querySelector('input'); if (i) i.focus(); }
    }
  }

  function dateParts() {
    var out = [], first = '';
    var none = $('input[name="uask-date"][value="none"]');
    if (none && none.checked) return { text: '', none: true, iso: '' };
    DATES.forEach(function (d) {
      var i = $('input[name="uask-date"][value="' + d.k + '"]');
      if (i && i.checked) { out.push(d.text); if (!first && d.iso) first = d.iso; }
    });
    if (state.day) {
      var p = state.day.split('-'), dd = new Date(+p[0], +p[1] - 1, +p[2]);
      out.unshift(mdw(dd)); first = state.day;
    }
    return { text: out.join('か、'), none: false, iso: first };
  }

  function message() {
    var c = courseOf(state.course);
    var base = state.baseMsg || (c ? c.msg : '空き日程を知りたいです。');
    var d = dateParts(), p = state.people, tail = '';
    if (d.none && p) tail = '日にちはまだ未定で、人数は' + p + 'です。';
    else if (d.none) tail = '日にちはまだ未定です。';
    else if (d.text && p) tail = '希望日は' + d.text + '、人数は' + p + 'です。';
    else if (d.text) tail = '希望日は' + d.text + 'です。';
    else if (p) tail = '人数は' + p + 'です。';
    var note = ($('#uask-note').value || '').replace(/\s+/g, ' ').trim();
    return base + tail + (note ? '\n' + note : '');
  }

  function render(pop) {
    var text = message();
    $$('[data-uask-bubble]').forEach(function (b) {
      if (b.textContent === text) return;
      b.textContent = text;
      if (pop) { b.classList.remove('is-new'); void b.offsetWidth; b.classList.add('is-new'); }
    });
    /* フォームの行き先：種別・日にち・人数・文面を入れて、フォームの所へ */
    var c = courseOf(state.course), d = dateParts();
    var q = [];
    q.push('from=' + encodeURIComponent(state.from));
    if (c) q.push('category=' + encodeURIComponent(c.cat));
    if (d.iso) q.push('date=' + d.iso);
    if (state.people) q.push('people=' + encodeURIComponent(state.people));
    q.push('msg=' + encodeURIComponent(text));
    $('[data-uask-form]').setAttribute('href', '/contact/?' + q.join('&') + '#form');
  }

  function copy(text) {
    if (navigator.clipboard && window.isSecureContext) {
      return navigator.clipboard.writeText(text).catch(function () { return legacyCopy(text); });
    }
    return legacyCopy(text);
  }
  function legacyCopy(text) {
    return new Promise(function (res, rej) {
      try {
        var ta = document.createElement('textarea');
        ta.value = text; ta.setAttribute('readonly', ''); ta.style.cssText = 'position:fixed;left:0;top:0;opacity:0;font-size:16px';
        dlg.appendChild(ta); ta.select(); ta.setSelectionRange(0, text.length);
        var ok = document.execCommand('copy'); dlg.removeChild(ta);
        ok ? res() : rej();
      } catch (e) { rej(e); }
    });
  }

  function done(ok) {
    var box = $('[data-uask-ok]');
    box.classList.toggle('is-ng', !ok);
    $('[data-uask-ok-text]').textContent = ok ? '文面をコピーしました' : '下の文面を、LINEで送ってください';
    $('[data-uask-done-label]').textContent = ok ? 'コピーした文面' : '送る文面（長押しでコピーできます）';
    $('[data-uask-ok] i').innerHTML = ok ? ICON_CHECK : ICON_BANG;
    dlg.classList.add('is-done');
    var dn = $('[data-uask-done]'); dn.scrollTop = 0;
    try { dn.focus({ preventScroll: true }); } catch (e) {}
  }

  /* うしろのページを止める。スクロールバーの幅ぶん、ページが横にずれないように埋める */
  function lock() {
    var de = document.documentElement, gap = window.innerWidth - de.clientWidth;
    de.style.overflow = 'hidden';
    if (gap > 0) de.style.paddingRight = gap + 'px';
  }
  function unlock() { var de = document.documentElement; de.style.overflow = ''; de.style.paddingRight = ''; }

  function open(ctx) {
    ctx = ctx || {};
    if (!window.HTMLDialogElement || !document.createElement('dialog').showModal) return false;
    build();
    if (dlg.open) return true;
    lastFocus = document.activeElement;
    var c = courseOf(ctx.course);
    state = {
      course: c ? c.k : '',
      baseMsg: (c && ctx.msg) ? String(ctx.msg) : '',
      from: String(ctx.from || 'site').replace(/[^a-zA-Z0-9_-]/g, '').slice(0, 28) + '-ask',
      people: '', day: ''
    };
    /* いったん全部はずす */
    $$('input[type="radio"],input[type="checkbox"]').forEach(function (i) { i.checked = false; });
    $$('.uask-chip').forEach(function (l) { l.removeAttribute('data-was'); });
    $('#uask-note').value = '';
    $('input[name="uask-day"]').min = iso(jstToday());
    /* 海況カードから来たときは、その日を入れておく */
    setDay(/^\d{4}-\d{2}-\d{2}$/.test(ctx.day || '') ? ctx.day : '');
    if (c) { var ci = $('input[name="uask-course"][value="' + c.k + '"]'); if (ci) ci.checked = true; }
    collapseCourse(!!c);
    state.title = ctx.title || '';
    setTitle();
    dlg.classList.remove('is-done', 'is-closing');
    $('.uask__sheet').style.setProperty('--drag', '0px');
    render(false);
    lock();
    var px = window.pageXOffset, py = window.pageYOffset;
    dlg.showModal();
    var body = $('.uask__body'); if (body) body.scrollTop = 0;
    /* 開いた直後は、閉じるボタンではなくシートそのものに（スマホでキーボードを出さない） */
    try { $('.uask__sheet').setAttribute('tabindex', '-1'); $('.uask__sheet').focus({ preventScroll: true }); } catch (e) {}
    /* 開くときのフォーカスで、外わくやうしろのページが動いていたら戻す（settle() の説明） */
    settle();
    if (window.pageXOffset !== px || window.pageYOffset !== py) window.scrollTo(px, py);
    return true;
  }

  function close() {
    if (!dlg || !dlg.open || dlg.classList.contains('is-closing')) return;
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var finish = function () {
      dlg.classList.remove('is-closing');
      if (dlg.open) dlg.close();
      unlock();
      if (lastFocus && lastFocus.focus) { try { lastFocus.focus({ preventScroll: true }); } catch (e) {} }
    };
    if (reduce) { finish(); return; }
    dlg.classList.add('is-closing');
    setTimeout(finish, 250);
  }

  window.UmiAsk = { open: open, close: close };
})();
