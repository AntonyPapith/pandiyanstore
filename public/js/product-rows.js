(function () {
  "use strict";
  var rows = document.getElementById("productRows");
  var template = document.getElementById("productRowTemplate");
  var addButton = document.getElementById("addProductRow");
  var nextIndex = 0;
  function renumberRows() {
    var entries = rows.querySelectorAll(".product-entry");
    for (var i = 0; i < entries.length; i += 1) {
      entries[i].querySelector(".product-row-number").textContent = i + 1;
      entries[i].querySelector(".remove-product-row").hidden = entries.length === 1;
    }
  }
  function addRow() {
    var fragment = template.content.cloneNode(true);
    var entry = fragment.querySelector(".product-entry");
    var fields = entry.querySelectorAll("[data-name]");
    for (var i = 0; i < fields.length; i += 1) {
      var fieldName = fields[i].getAttribute("data-name");
      fields[i].name = "products[" + nextIndex + "][" + fieldName + "]" + (fields[i].multiple ? "[]" : "");
    }
    nextIndex += 1;
    entry.querySelector(".remove-product-row").addEventListener("click", function () { entry.parentNode.removeChild(entry); renumberRows(); });
    rows.appendChild(fragment);
    renumberRows();
  }
  addButton.addEventListener("click", addRow);
  addRow();
}());
