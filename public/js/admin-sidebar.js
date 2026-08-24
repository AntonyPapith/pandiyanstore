const adminSidebar = document.getElementById("adminSidebar");
const adminSidebarToggle = document.getElementById("sidebarToggle");
const adminSidebarClose = document.getElementById("adminSidebarClose");
const adminSidebarBackdrop = document.getElementById("adminSidebarBackdrop");

function openAdminSidebar() {
  adminSidebar.classList.add("open");
  adminSidebarBackdrop.classList.add("open");
  adminSidebarBackdrop.setAttribute("aria-hidden", "false");
  adminSidebarToggle.setAttribute("aria-expanded", "true");
}

function closeAdminSidebar() {
  adminSidebar.classList.remove("open");
  adminSidebarBackdrop.classList.remove("open");
  adminSidebarBackdrop.setAttribute("aria-hidden", "true");
  adminSidebarToggle.setAttribute("aria-expanded", "false");
}

adminSidebarToggle?.setAttribute("aria-expanded", "false");
adminSidebarToggle?.addEventListener("click", event => {
  event.stopPropagation();
  adminSidebar.classList.contains("open") ? closeAdminSidebar() : openAdminSidebar();
});
adminSidebarClose?.addEventListener("click", closeAdminSidebar);
adminSidebarBackdrop?.addEventListener("click", closeAdminSidebar);
document.addEventListener("pointerdown", event => {
  if (window.innerWidth > 760 || !adminSidebar?.classList.contains("open")) return;
  if (adminSidebar.contains(event.target) || adminSidebarToggle?.contains(event.target)) return;
  closeAdminSidebar();
});
window.addEventListener("resize", () => { if (window.innerWidth > 760) closeAdminSidebar(); });
