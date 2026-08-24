document.head.insertAdjacentHTML("beforeend", `<style>.password-wrap{position:relative}.password-wrap input{padding-right:48px}.password-eye{position:absolute;right:5px;top:50%;width:38px;height:38px;transform:translateY(-50%);border:0;border-radius:50%;background:transparent;color:#08033D;cursor:pointer}.password-eye.is-visible{background:#FDCCE6}.auth-switch{text-align:center!important;margin:20px 0 0!important}.auth-switch a{color:#08033D;font-weight:700}</style>`);
document.querySelectorAll(".password-eye").forEach(button => {
  button.addEventListener("click", () => {
    const input = document.getElementById(button.dataset.password);
    const show = input.type === "password";
    input.type = show ? "text" : "password";
    button.setAttribute("aria-label", show ? "Hide password" : "Show password");
    button.classList.toggle("is-visible", show);
  });
});
