(function () {
  "use strict";
  var variants = window.PRODUCT_VARIANTS || [];
  var colorOptions = document.getElementById("variantColor");
  var sizeOptions = document.getElementById("variantSize");
  var image = document.getElementById("variantImage");
  var thumbnails = document.getElementById("variantThumbnails");
  var price = document.getElementById("variantPrice");
  var originalPrice = document.getElementById("variantOriginalPrice");
  var stock = document.getElementById("variantStock");
  var description = document.getElementById("variantDescription");
  var form = document.getElementById("variantCartForm");
  var wishlistForm = document.getElementById("variantWishlistForm");
  var cartButton = document.getElementById("variantCartButton");
  function fillImages(items) {
    if (!thumbnails || !image) return;
    thumbnails.innerHTML = "";
    (items || []).forEach(function (source, index) {
      var button = document.createElement("button");
      var thumbnail = document.createElement("img");
      button.type = "button"; button.className = "variant-thumbnail" + (index === 0 ? " active" : "");
      thumbnail.src = source; thumbnail.alt = ""; button.appendChild(thumbnail);
      button.addEventListener("click", function () {
        image.src = source;
        thumbnails.querySelectorAll("button").forEach(function (item) { item.classList.remove("active"); });
        button.classList.add("active");
      });
      thumbnails.appendChild(button);
    });
    thumbnails.hidden = (items || []).length < 2;
  }
  function money(value) { return "₹" + Number(value).toLocaleString("en-IN", { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
  function unique(field, list) { var seen = {}; return list.map(function (item) { return item[field] || ""; }).filter(function (value) { if (seen[value]) return false; seen[value] = true; return true; }); }
  function selected(container) { var button = container && container.querySelector("button.active"); return button ? button.getAttribute("data-value") : null; }
  function fill(container, values, type, preferred) {
    if (!container) return;
    container.innerHTML = "";
    values.forEach(function (value, index) {
      var button = document.createElement("button");
      button.type = "button"; button.className = "variant-option " + type + "-option"; button.setAttribute("data-value", value);
      button.setAttribute("aria-label", (type === "color" ? "Color " : "Size ") + (value || "Default")); button.title = value || "Default";
      if (type === "color") { var swatch = value && window.CSS && CSS.supports("color", value) ? value : "#FDCCE6"; button.style.setProperty("--swatch", swatch); button.innerHTML = "<span></span>"; }
      else button.textContent = value || "–";
      if ((preferred !== null && value === preferred) || (preferred === null && index === 0)) button.classList.add("active");
      button.addEventListener("click", function () {
        var buttons = container.querySelectorAll("button");
        for (var i = 0; i < buttons.length; i += 1) buttons[i].classList.remove("active");
        button.classList.add("active"); render(type === "color");
      });
      container.appendChild(button);
    });
  }
  function render(colorChanged) {
    var color = selected(colorOptions);
    var matches = variants.filter(function (item) { return color === null || (item.color || "") === color; });
    var oldSize = colorChanged ? null : selected(sizeOptions);
    if (sizeOptions) fill(sizeOptions, unique("size", matches), "size", oldSize);
    var size = selected(sizeOptions);
    var variant = matches.filter(function (item) { return size === null || (item.size || "") === size; })[0] || matches[0] || variants[0];
    if (!variant) return;
    image.src = variant.image; fillImages(variant.images || [variant.image]); price.textContent = money(variant.sale_price === null ? variant.price : variant.sale_price);
    originalPrice.textContent = variant.sale_price === null ? "" : money(variant.price);
    stock.textContent = variant.quantity > 0 ? variant.quantity + " in stock" : "Out of stock"; stock.classList.toggle("out", variant.quantity < 1);
    description.textContent = variant.description; form.action = variant.cart_url; cartButton.disabled = variant.quantity < 1; cartButton.textContent = variant.quantity > 0 ? "Add to cart" : "Out of stock";
    if (wishlistForm) {
      wishlistForm.action = variant.wishlist_url;
      var wishlistButton = wishlistForm.querySelector("button");
      if (wishlistButton) wishlistButton.classList.toggle("active", Boolean(variant.wishlisted));
    }
  }
  if (colorOptions) fill(colorOptions, unique("color", variants), "color", null);
  render(true);
}());
