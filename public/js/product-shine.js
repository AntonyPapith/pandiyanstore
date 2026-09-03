document.addEventListener("click", event => {
  const card = event.target.closest(".product-card");
  if (!card || event.target.closest("button, form, input")) return;
  card.classList.remove("product-shine-active");
  void card.offsetWidth;
  card.classList.add("product-shine-active");
  const link = event.target.closest("a.product-detail-link");
  if (link) {
    event.preventDefault();
    sessionStorage.setItem("product-return:" + location.pathname, card.id);
    card.classList.add("product-card-leaving");
    setTimeout(() => { location.href = link.href; }, 180);
    return;
  }
  setTimeout(() => card.classList.remove("product-shine-active"), 700);
});

document.querySelectorAll(".product-card img[data-product-images]").forEach(image => {
  let images = [];
  let timer;
  let index = 0;
  try { images = JSON.parse(image.dataset.productImages || "[]"); } catch (_) { images = []; }
  if (images.length < 2) return;
  const card = image.closest(".product-card");
  card.addEventListener("mouseenter", () => {
    clearInterval(timer);
    timer = setInterval(() => { index = (index + 1) % images.length; image.src = images[index]; }, 1100);
  });
  card.addEventListener("mouseleave", () => { clearInterval(timer); index = 0; image.src = images[0]; });
});

window.addEventListener("pageshow", () => {
  const targetId = location.hash.slice(1) || sessionStorage.getItem("product-return:" + location.pathname);
  const target = targetId && document.getElementById(targetId);
  if (target) requestAnimationFrame(() => target.scrollIntoView({ block: "center" }));
});

document.querySelectorAll("[data-product-color]").forEach(item => {
  const color = item.dataset.productColor;
  if (color && window.CSS && CSS.supports("color", color)) item.style.setProperty("--product-color", color);
});
