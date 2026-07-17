(() => {
  document.querySelectorAll("[data-copy-target]").forEach((button) => {
    button.addEventListener("click", async () => {
      const input = document.getElementById(button.dataset.copyTarget);
      const status = document.querySelector("[data-copy-status]");
      if (!input) return;
      try {
        await navigator.clipboard.writeText(input.value);
      } catch (_) {
        input.focus();
        input.select();
        document.execCommand("copy");
      }
      button.textContent = "コピーしました ✓";
      if (status) status.textContent = "受講生サイトのURLをコピーしました。お客様へのメッセージに貼り付けられます。";
      setTimeout(() => { button.textContent = "URLをコピー"; }, 2500);
    });
  });
})();
