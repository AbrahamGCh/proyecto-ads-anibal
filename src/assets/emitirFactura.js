document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("emitir-factura-form");
  const nextButton = document.getElementById("btnSiguiente");

  nextButton.addEventListener("click", (event) => {
    event.preventDefault();

    // Validar formulario
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    // Recopilar productos seleccionados
    const selectedProducts = [];
    const productCheckboxes = document.querySelectorAll("[data-product-checkbox]:checked");

    productCheckboxes.forEach((checkbox) => {
      const productId = checkbox.getAttribute("data-product-id");
      const productName = checkbox.getAttribute("data-product-name");
      const productPrice = checkbox.getAttribute("data-product-price");

      const amountInput = document.querySelector(`[data-product-amount][data-product-id="${productId}"]`);

      const productAmount = amountInput ? parseFloat(amountInput.value) || 0 : 0;

      // Agregar producto al array
      selectedProducts.push({
        id: productId,
        name: productName,
        price: parseFloat(productPrice),
        amount: productAmount,
      });
    });

    // Crear campo oculto para enviar los productos seleccionados
    const productsField = document.createElement("input");
    productsField.type = "hidden";
    productsField.name = "productsArray";
    productsField.value = JSON.stringify(selectedProducts);
    form.appendChild(productsField);

    // Campo oculto para identificar el botón
    const btnSiguienteField = document.createElement("input");
    btnSiguienteField.type = "hidden";
    btnSiguienteField.name = "btnSiguiente";
    btnSiguienteField.value = "siguiente";
    form.appendChild(btnSiguienteField);

    // Enviar formulario
    form.submit();
  });
});
