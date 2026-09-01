const paymentForm = document.getElementById("paymentForm");
const paymentButton = document.getElementById("paymentButton");
const paymentError = document.getElementById("paymentError");
const upiOption = paymentForm.querySelector('[value="upi"]');
let upiOpened = false;

function showPaymentError(message) {
  paymentError.textContent = message;
  paymentError.hidden = false;
}

paymentForm.addEventListener("submit", event => {
  const method = paymentForm.querySelector('[name="payment_method"]:checked').value;
  if (method === "cod" || upiOpened) return;

  event.preventDefault();
  const upiUrl = window.PANDIAN_UPI && window.PANDIAN_UPI.url;
  if (!upiUrl) {
    showPaymentError("Direct UPI payment is temporarily unavailable. Please choose Cash on delivery.");
    return;
  }

  paymentError.hidden = true;
  upiOpened = true;
  paymentButton.innerHTML = "I have paid — place order <span>&rarr;</span>";
  window.location.href = upiUrl;
});

upiOption.addEventListener("change", event => {
  if (!event.target.checked) return;
  upiOpened = false;
  paymentButton.innerHTML = "Open GPay / UPI <span>&rarr;</span>";
});

paymentForm.querySelector('[value="cod"]').addEventListener("change", event => {
  if (!event.target.checked) return;
  upiOpened = false;
  paymentButton.innerHTML = "Confirm order <span>&rarr;</span>";
});
