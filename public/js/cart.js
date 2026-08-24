const money = value => `₹${Number(value).toLocaleString("en-IN", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
const message = document.getElementById("cartMessage");

function showMessage(text) {
  message.textContent = text;
  message.hidden = false;
  clearTimeout(showMessage.timer);
  showMessage.timer = setTimeout(() => { message.hidden = true; }, 4000);
}

function calculateTotal() {
  const total = [...document.querySelectorAll(".cart-item")].reduce((sum, row) => {
    const value = row.querySelector(".quantity-form input").value;
    return value === "" ? sum : sum + Number(row.dataset.unitPrice) * Number(value);
  }, 0);
  document.getElementById("cartTotal").textContent = money(total);
}

document.querySelectorAll(".quantity-form input").forEach(input => {
  let timer;
  let savedQuantity = Number(input.value);

  input.addEventListener("input", () => {
    clearTimeout(timer);
    const item = input.closest(".cart-item");
    const maximum = Number(input.max);

    if (input.value === "") {
      item.querySelector(".cart-line-total").textContent = "—";
      calculateTotal();
      return;
    }

    const entered = Number(input.value);
    if (!Number.isInteger(entered) || entered < 1) {
      showMessage("Quantity must be at least 1.");
      return;
    }
    if (entered > maximum) {
      showMessage(`Only ${maximum} item${maximum === 1 ? " is" : "s are"} available in stock.`);
      input.value = savedQuantity;
      item.querySelector(".cart-line-total").textContent = money(Number(item.dataset.unitPrice) * savedQuantity);
      calculateTotal();
      return;
    }

    item.querySelector(".cart-line-total").textContent = money(Number(item.dataset.unitPrice) * entered);
    calculateTotal();

    timer = setTimeout(async () => {
      const form = input.form;
      const response = await fetch(form.action, {
        method: "PATCH",
        headers: { "Accept": "application/json", "Content-Type": "application/json", "X-CSRF-TOKEN": form.querySelector('[name="_token"]').value },
        body: JSON.stringify({ quantity: entered })
      });
      if (!response.ok) {
        showMessage("Quantity could not be updated. Please try again.");
        return;
      }
      const data = await response.json();
      savedQuantity = data.quantity;
      input.value = data.quantity;
      item.querySelector(".cart-line-total").textContent = money(data.line_amount);
      document.getElementById("cartTotal").textContent = money(data.total);
    }, 300);
  });

  input.addEventListener("blur", () => {
    if (input.value === "") {
      input.value = savedQuantity;
      const item = input.closest(".cart-item");
      item.querySelector(".cart-line-total").textContent = money(Number(item.dataset.unitPrice) * savedQuantity);
      calculateTotal();
    }
  });
});
