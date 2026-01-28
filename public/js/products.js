const tableBody = document.getElementById('productsTable');
const grandTotalEl = document.getElementById('grandTotal');
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Load products on page load
fetchProducts();

document.getElementById('productForm').addEventListener('submit', function (e) {
    e.preventDefault();

    fetch('/products', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            product_name: document.getElementById('product_name').value,
            quantity: document.getElementById('quantity').value,
            price: document.getElementById('price').value
        })
    })
    .then(() => {
        this.reset();
        fetchProducts();
    });
});

function fetchProducts() {
    fetch('/products')
        .then(res => res.json())
        .then(data => {
            tableBody.innerHTML = '';
            let grandTotal = 0;

            data.forEach(item => {
                const total = item.quantity * item.price;
                grandTotal += total;

                tableBody.innerHTML += `
                    <tr>
                        <td>${item.product_name}</td>
                        <td>${item.quantity}</td>
                        <td>$${Number(item.price).toFixed(2)}</td>
                        <td>${item.submitted_at}</td>
                        <td>$${total.toFixed(2)}</td>
                    </tr>
                `;
            });

            grandTotalEl.textContent = `$${grandTotal.toFixed(2)}`;
        });
}
