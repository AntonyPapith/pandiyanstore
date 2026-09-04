(() => {
  "use strict";

  const form = document.getElementById("paymentForm");
  const button = document.getElementById("paymentButton");
  const errorBox = document.getElementById("paymentError");
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
  const endpoints = window.PANDIAN_RAZORPAY || {};

  if (!form || !button) return;

  const showError = message => {
    errorBox.textContent = message || "Payment failed. No order was placed.";
    errorBox.hidden = false;
  };

  const setBusy = busy => {
    button.disabled = busy;
    button.innerHTML = busy ? "Please wait…" : 'Pay now <span>→</span>';
  };

  const post = async (url, body = {}) => {
    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Accept": "application/json",
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": csrf,
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify(body),
    });
    const data = await response.json().catch(() => ({}));
    if (!response.ok) throw new Error(data.message || "Payment could not be processed.");
    return data;
  };

  form.addEventListener("submit", async event => {
    const selected = form.querySelector('[name="payment_method"]:checked');
    if (selected?.value === "cod") return;

    event.preventDefault();
    if (!selected || selected.value !== "razorpay") {
      showError("Please select an available payment method.");
      return;
    }
    if (typeof window.Razorpay !== "function") {
      showError("Razorpay could not load. Check your connection and try again.");
      return;
    }

    errorBox.hidden = true;
    setBusy(true);

    try {
      const checkout = await post(endpoints.create_url);
      const razorpay = new window.Razorpay({
        key: checkout.key,
        amount: checkout.amount,
        currency: checkout.currency,
        name: checkout.name,
        description: "Google Pay / UPI payment",
        order_id: checkout.order_id,
        prefill: checkout.customer,
        theme: { color: "#CD0000" },
        modal: {
          ondismiss: () => {
            setBusy(false);
            showError("Payment was cancelled. Your order was not placed.");
          },
        },
        handler: async response => {
          button.innerHTML = "Verifying payment…";
          try {
            const verified = await post(endpoints.verify_url, response);
            window.location.assign(verified.redirect);
          } catch (error) {
            setBusy(false);
            showError(error.message);
          }
        },
      });

      razorpay.on("payment.failed", response => {
        setBusy(false);
        showError(response.error?.description || "Payment was not completed. Your order was not placed and no amount was charged by the website.");
      });
      razorpay.open();
    } catch (error) {
      setBusy(false);
      showError(error.message);
    }
  });

  form.querySelectorAll('[name="payment_method"]').forEach(option => {
    option.addEventListener("change", () => {
      errorBox.hidden = true;
      button.innerHTML = option.value === "cod" ? 'Confirm order <span>→</span>' : 'Pay now <span>→</span>';
    });
  });
})();
