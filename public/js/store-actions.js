(function () {
  "use strict";

  function updateNotification(selector, value) {
    var nodes = document.querySelectorAll(selector);
    for (var i = 0; i < nodes.length; i += 1) {
      nodes[i].textContent = value;
      nodes[i].classList.remove("notification-bump");
      void nodes[i].offsetWidth;
      nodes[i].classList.add("notification-bump");
    }
  }

  function sendForm(form, extraData, done, failed) {
    var data = new FormData(form);
    var key;
    for (key in extraData) {
      if (Object.prototype.hasOwnProperty.call(extraData, key)) data.append(key, extraData[key]);
    }

    var request = new XMLHttpRequest();
    request.open("POST", form.action, true);
    request.setRequestHeader("Accept", "application/json");
    request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    request.onreadystatechange = function () {
      if (request.readyState !== 4) return;
      var result;
      try {
        result = JSON.parse(request.responseText);
      } catch (error) {
        failed("Unable to update. Please try again.");
        return;
      }
      if (request.status >= 200 && request.status < 300) done(result);
      else failed(result.message || "Unable to update. Please try again.");
    };
    request.onerror = function () { failed("Connection failed. Please try again."); };
    request.send(data);
  }

  document.addEventListener("submit", function (event) {
    var form = event.target;
    var isWishlist = form.classList.contains("wishlist-toggle");
    var isCart = form.classList.contains("add-cart-form");
    if (!isWishlist && !isCart) return;
    event.preventDefault();

    var button = form.querySelector("button");
    if (!button) return;
    if (isCart && (form.getAttribute("data-busy") === "true" || button.disabled)) return;

    var originalText = button.textContent;
    var wasActive = button.classList.contains("active");
    var desiredActive = !wasActive;
    var requestId = String(Number(form.getAttribute("data-request-id") || 0) + 1);
    form.setAttribute("data-request-id", requestId);

    if (isWishlist) button.classList.toggle("active", desiredActive);
    if (isCart) {
      form.setAttribute("data-busy", "true");
      button.disabled = true;
      button.textContent = "Adding...";
    }

    sendForm(form, isWishlist ? { wishlisted: desiredActive ? "1" : "0" } : {}, function (result) {
      if (isWishlist) {
        if (form.getAttribute("data-request-id") !== requestId) return;
        var forms = document.querySelectorAll(".wishlist-toggle");
        for (var i = 0; i < forms.length; i += 1) {
          if (forms[i].action === form.action) {
            var heart = forms[i].querySelector("button");
            if (heart) heart.classList.toggle("active", Boolean(result.active));
          }
        }
        updateNotification(".product-wishlist-link span", result.wishlist_count);
        var heading = document.querySelector("h1");
        var card = form.closest ? form.closest(".product-card") : null;
        if (!result.active && heading && heading.textContent.replace(/^\s+|\s+$/g, "") === "My Wishlist" && card) card.parentNode.removeChild(card);
      } else {
        updateNotification(".product-cart-link span, .cart-icon span", result.cart_count);
        button.textContent = "Added";
        window.setTimeout(function () {
          button.textContent = originalText;
          button.disabled = false;
          form.setAttribute("data-busy", "false");
        }, 700);
      }
    }, function (message) {
      if (isWishlist && form.getAttribute("data-request-id") === requestId) button.classList.toggle("active", wasActive);
      if (isCart) {
        button.textContent = originalText;
        button.disabled = false;
        form.setAttribute("data-busy", "false");
      }
      window.alert(message);
    });
  });
}());
