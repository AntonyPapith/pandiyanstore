document.addEventListener("click", event => {
  const card = event.target.closest(".product-card");
  if (!card || event.target.closest("button, form, input, a")) return;
  card.classList.remove("product-shine-active");
  void card.offsetWidth;
  card.classList.add("product-shine-active");
  setTimeout(() => card.classList.remove("product-shine-active"), 700);
});
