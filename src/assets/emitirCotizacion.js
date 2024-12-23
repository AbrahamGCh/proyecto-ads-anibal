document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("emitir-cotizacion-form");
  const nextButton = document.getElementById("btnSiguiente");

  nextButton.addEventListener("click", (event) => {
    event.preventDefault();

    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    const selectedProducts = [];
    const productCheckboxes = document.querySelectorAll("[data-product-checkbox]:checked");

    productCheckboxes.forEach((checkbox) => {
      const productId = checkbox.getAttribute("data-product-id");
      const productName = checkbox.getAttribute("data-product-name");
      const productPrice = checkbox.getAttribute("data-product-price");

      const amountInput = document.querySelector(`[data-product-amount][data-product-id="${productId}"]`);

      const productAmount = amountInput ? parseFloat(amountInput.value) || 0 : 0;

      selectedProducts.push({
        id: productId,
        name: productName,
        price: parseFloat(productPrice),
        amount: productAmount,
      });
    });

    const productsField = document.createElement("input");
    productsField.type = "hidden";
    productsField.name = "productsArray";
    productsField.value = JSON.stringify(selectedProducts);
    form.appendChild(productsField);

    const btnSiguienteField = document.createElement("input");
    btnSiguienteField.type = "hidden";
    btnSiguienteField.name = "btnSiguiente";
    btnSiguienteField.value = "siguiente";
    form.appendChild(btnSiguienteField);

    form.submit();
  });
});
