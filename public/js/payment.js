const paymentForm = document.getElementById("paymentForm");
const paymentButton = document.getElementById("paymentButton");
const paymentError = document.getElementById("paymentError");
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let razorpayOpening = false;

function showPaymentError(message) {
  paymentError.textContent = message;
  paymentError.hidden = false;
  paymentButton.disabled = false;
  paymentButton.innerHTML = "Confirm order <span>&rarr;</span>";
  razorpayOpening = false;
}

function startRazorpayPayment() {
  if (razorpayOpening) return;
  if (typeof Razorpay === "undefined") {
    showPaymentError("Razorpay Checkout could not load. Check your internet connection and try again.");
    return;
  }

  const order = window.PANDIAN_RAZORPAY.checkout;
  if (!order) {
    showPaymentError("Online payment is temporarily unavailable. Refresh the page and try again.");
    return;
  }

  razorpayOpening = true;
  paymentError.hidden = true;
  paymentButton.disabled = true;
  paymentButton.textContent = "Opening payment...";

  try {
    const checkout = new Razorpay({
      key: order.key,
      amount: order.amount,
      currency: order.currency,
      name: order.name,
      description: "Pandiyan Store order payment",
      image: window.PANDIAN_RAZORPAY.logo,
      order_id: order.order_id,
      prefill: order.customer,
      theme: { color: "#08033D" },
      config: {
        display: {
          blocks: {
            upi_apps: {
              name: "Pay via UPI",
              instruments: [{ method: "upi" }]
            }
          },
          sequence: ["block.upi_apps"],
          preferences: { show_default_blocks: true }
        }
      },
      modal: { ondismiss: () => showPaymentError("Payment was cancelled. You can try again.") },
      handler: async response => {
        paymentButton.textContent = "Verifying payment...";
        try {
          const verifyResponse = await fetch(window.PANDIAN_RAZORPAY.verifyUrl, {
            method: "POST",
            headers: { "Accept": "application/json", "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
            body: JSON.stringify(response)
          });
          const result = await verifyResponse.json();
          if (!verifyResponse.ok) return showPaymentError(result.message || "Payment verification failed.");
          window.location.href = result.redirect;
        } catch (error) {
          showPaymentError("Payment completed, but verification failed. Please contact support before paying again.");
        }
      }
    });
    checkout.on("payment.failed", response => showPaymentError(response.error.description || "Payment failed. Please try again."));
    checkout.open();
  } catch (error) {
    showPaymentError(error.message || "Unable to start payment. Please try again.");
  }
}

paymentForm.addEventListener("submit", event => {
  const method = paymentForm.querySelector('[name="payment_method"]:checked').value;
  if (method === "cod") return;
  event.preventDefault();
  startRazorpayPayment();
});

paymentForm.querySelector('[value="upi"]').addEventListener("change", event => {
  if (event.target.checked) startRazorpayPayment();
});
