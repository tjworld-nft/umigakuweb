(() => {
  // 受講者のアカウントを触るボタンは、押し間違いで実行されないようにする。
  // CSPでインラインの onclick は使えないのでここで付ける。
  document.querySelectorAll("[data-confirm]").forEach((button) => {
    button.addEventListener("click", (event) => {
      if (!window.confirm(button.dataset.confirm)) event.preventDefault();
    });
  });

  // 受講生サイトのURLだけでなく、受講者IDや復旧コードのような
  // 「書き写すと間違えるもの」もコピーできるようにする。
  document.querySelectorAll("[data-copy-target]").forEach((button) => {
    const originalLabel = button.textContent;
    const message = button.dataset.copyMessage || "コピーしました。メモ帳やメッセージに貼り付けて保存できます。";
    button.addEventListener("click", async () => {
      const target = document.getElementById(button.dataset.copyTarget);
      const status = button.closest("section, .credentials")?.querySelector("[data-copy-status]")
        || document.querySelector("[data-copy-status]");
      if (!target) return;
      const isField = "value" in target && target.tagName !== "DIV";
      const text = isField ? target.value : (target.textContent || "").trim();
      let copied = true;
      try {
        await navigator.clipboard.writeText(text);
      } catch (_) {
        if (isField) {
          target.focus();
          target.select();
          copied = document.execCommand("copy");
        } else {
          const range = document.createRange();
          range.selectNodeContents(target);
          const selection = window.getSelection();
          selection.removeAllRanges();
          selection.addRange(range);
          copied = document.execCommand("copy");
        }
      }
      button.textContent = copied ? "コピーしました ✓" : "選択しました";
      if (status) status.textContent = copied ? message : "うまくコピーできませんでした。選択した文字を長押しして手動でコピーしてください。";
      setTimeout(() => { button.textContent = originalLabel; }, 2500);
    });
  });
})();
