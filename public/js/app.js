// Ask for confirmation before submitting forms marked with data-confirm
// (used by the Delete and Archive buttons).
document.addEventListener('submit', function (event) {
    const message = event.target.dataset.confirm;

    if (message && !window.confirm(message)) {
        event.preventDefault();
    }
});

// Reject files that are too large before they are uploaded. Very large files
// would exceed PHP's post_max_size and never reach the server-side validation.
document.querySelectorAll('input[type="file"][data-max-bytes]').forEach(function (input) {
    input.addEventListener('change', function () {
        const file = input.files[0];

        if (file && file.size > Number(input.dataset.maxBytes)) {
            window.alert(input.dataset.tooLarge);
            input.value = '';
        }
    });
});

// Record Sale: show the selected product's stock and the running total.
// The server still checks the stock when the sale is submitted.
(function () {
    const product = document.getElementById('product_id');
    const quantity = document.getElementById('quantity');
    const total = document.getElementById('sale-total');
    const stockHint = document.getElementById('stock-hint');

    if (!product || !quantity || !total || !stockHint) {
        return;
    }

    const peso = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' });

    function update() {
        const option = product.selectedOptions[0];
        const price = Number(option.dataset.price || 0);
        const stock = option.dataset.stock;
        const count = parseInt(quantity.value, 10) || 0;

        total.textContent = peso.format(price * count);

        if (stock === undefined) {
            stockHint.textContent = '';
        } else if (count > Number(stock)) {
            stockHint.textContent = 'Only ' + stock + ' in stock.';
        } else {
            stockHint.textContent = stock + ' in stock.';
        }

        stockHint.classList.toggle('hint-error', stock !== undefined && count > Number(stock));
    }

    product.addEventListener('change', update);
    quantity.addEventListener('input', update);
    update();
})();
