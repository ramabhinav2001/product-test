<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Inventory</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Product Inventory</h2>

    
    <form id="productForm" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Product Name</label>
            <input type="text" class="form-control" id="product_name" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Quantity in Stock</label>
            <input type="number" class="form-control" id="quantity" min="0" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Price per Item</label>
            <input type="number" step="0.01" class="form-control" id="price" min="0" required>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Add</button>
        </div>
    </form>

    
    <table class="table table-bordered table-striped mt-5">
        <thead class="table-dark">
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Datetime Submitted</th>
                <th>Total Value</th>
            </tr>
        </thead>
        <tbody id="productsTable"></tbody>
        <tfoot>
            <tr class="fw-bold">
                <td colspan="4" class="text-end">Grand Total</td>
                <td id="grandTotal">$0.00</td>
            </tr>
        </tfoot>
    </table>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('js/products.js') }}"></script>

    </body>
</html>
