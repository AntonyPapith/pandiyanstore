document.head.insertAdjacentHTML("beforeend", `<style>.password-wrap{position:relative}.password-wrap input{padding-right:48px}.password-eye{position:absolute;right:5px;top:50%;width:38px;height:38px;padding:0;transform:translateY(-50%);border:0;border-radius:50%;background:transparent;color:#08033D;cursor:pointer;display:grid;place-items:center}.password-eye svg{width:21px;height:21px}.password-eye .eye-closed{display:none}.password-eye.is-visible{background:#FDCCE6}.password-eye.is-visible .eye-open{display:none}.password-eye.is-visible .eye-closed{display:block}.auth-switch{text-align:center!important;margin:20px 0 0!important}.auth-switch a{color:#08033D;font-weight:700}</style>`);
document.querySelectorAll(".password-eye").forEach(button => {
  button.innerHTML = `<svg class="eye-open" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="2.7" stroke="currentColor" stroke-width="1.8"/></svg><svg class="eye-closed" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 3l18 18M10.6 6.2A10.7 10.7 0 0 1 12 6c6 0 9.5 6 9.5 6a17 17 0 0 1-2.7 3.3M6.2 6.2C3.8 8 2.5 12 2.5 12s3.5 6 9.5 6a10 10 0 0 0 3-.4M9.9 9.9a3 3 0 0 0 4.2 4.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`;
  button.addEventListener("click", () => {
    const input = document.getElementById(button.dataset.password);
    const show = input.type === "password";
    input.type = show ? "text" : "password";
    button.setAttribute("aria-label", show ? "Hide password" : "Show password");
    button.classList.toggle("is-visible", show);
  });
});
