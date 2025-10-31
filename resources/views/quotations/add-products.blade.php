@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Add Products to Quotation</h4>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="border rounded p-3 bg-light h-100">
                    <h6 class="mb-3">Quotation Details</h6>
                    <p><strong>Quotation ID:</strong> {{ $quotation->id }}</p>
                    <p><strong>Date:</strong> {{ $quotation->quotation_date }}</p>
                    <p><strong>Validity:</strong> {{ $quotation->validity_date }}</p>
                    <p><strong>Status:</strong>
                        <span class="badge {{ $quotation->status == 0 ? 'bg-warning' : 'bg-success' }}">
                            {{ $quotation->status == 0 ? 'Incomplete' : 'Completed' }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 bg-light h-100">
                    <h6 class="mb-3">Customer Details</h6>
                    @if (!empty($quotation->customer))
                        <p><strong>Name:</strong> {{ $quotation->customer->name }}</p>
                        <p><strong>Email:</strong> {{ $quotation->customer->email ?? 'N/A' }}</p>
                        <p><strong>Phone:</strong> {{ $quotation->customer->phone ?? 'N/A' }}</p>
                        <p><strong>Address:</strong> {{ $quotation->customer->address ?? 'N/A' }}</p>
                    @else
                        <p>No customer selected.</p>
                    @endif
                </div>
            </div>
        </div>


        <form action="{{ route('quotation.saveProducts', $quotation->id) }}" method="POST" id="addProductsForm">
            @csrf

            <div class="row mb-3">



            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="productSearch" class="form-label">Search Product (Name or Barcode)</label>
                    <input type="text" id="productSearch" class="form-control"
                        placeholder="Type product name or barcode...">
                </div>
                <div class="col-md-8">


                    <label for="productSelect" class="form-label">Select Products</label>
                    <select class="form-select" id="productSelect" multiple>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->sale_price }}"
                                data-gst="{{ $product->gst }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple.</small>
                </div>
            </div>

            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle" id="selectedProductsTable">
                    <thead class="table-secondary">
                        <tr>
                            <th>Product</th>
                            <th width="120">Quantity</th>
                            <th width="150">Sale Price (Rs.)</th>
                            <th width="150">GST %</th>
                            <th width="150">Total (Rs.)</th>
                            <th width="70">Remove</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <input type="hidden" name="selected_products" id="selected_products">
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4">Save & Continue</button>
            </div>
        </form>
    </div>

    <script>
        const searchInput = document.getElementById('productSearch');
        const productSelect = document.getElementById('productSelect');
        let searchTimeout = null;

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {


                fetch(`/search-products?q=${encodeURIComponent(query)}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })

                    .then(res => res.json())
                    .then(products => {
                        productSelect.innerHTML = '';
                        if (products.length === 0) {
                            const option = document.createElement('option');
                            option.textContent = 'No products found';
                            option.disabled = true;
                            productSelect.appendChild(option);
                            return;
                        }

                        products.forEach(p => {
                            const option = document.createElement('option');
                            option.value = p.id;
                            option.textContent =
                                `${p.name} `;
                            option.dataset.price = p.sale_price;
                            option.dataset.gst = p.gst;
                            productSelect.appendChild(option);
                        });
                    })
                    .catch(err => console.error('Error fetching products:', err));
            }, 400);

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const productSelect = document.getElementById('productSelect');
            const tableBody = document.querySelector('#selectedProductsTable tbody');
            const hiddenInput = document.getElementById('selected_products');
            const form = document.getElementById('addProductsForm');
            let selectedProducts = {};

            productSelect.addEventListener('change', () => {
                const options = Array.from(productSelect.selectedOptions);
                options.forEach(option => {
                    const id = option.value;
                    const name = option.textContent;
                    const price = parseFloat(option.dataset.price);
                    const gst = option.getAttribute('data-gst');
                    const total = 0
                    if (!selectedProducts[id]) {
                        selectedProducts[id] = {
                            name,
                            quantity: 1,
                            price,
                            gst,
                            total
                        };
                        renderTable();
                    }
                });
            });

            function renderTable() {
                tableBody.innerHTML = '';
                Object.entries(selectedProducts).forEach(([id, prod]) => {
                    const row = document.createElement('tr');
                    row.setAttribute('data-prod-id', id);
                    row.setAttribute('data-total',
                        (prod.price * prod.quantity).toFixed(2)
                    )

                    row.innerHTML = `
                <td>${prod.name}</td>
                <td><input type="number" min="1" name="prod_quantity" class="prod_quantity_input"  value="${prod.quantity}" class="form-control form-control-sm qty-input" data-id="${id}"></td>
                <td>${prod.price.toFixed(2)}</td>
                <td>${prod.gst}%</td>
                <td>${(prod.price * prod.quantity).toFixed(2)}</td>
                <td><button class="btn btn-sm btn-danger remove-btn" data-id="${id}">×</button></td>
            `;
                    tableBody.appendChild(row);
                });
                updateHiddenInput();
            }

            tableBody.addEventListener('input', e => {
                if (e.target.classList.contains('qty-input')) {
                    const id = e.target.dataset.id;
                    const qty = parseInt(e.target.value);
                    if (qty > 0) {
                        selectedProducts[id].quantity = qty;
                        renderTable();
                    }
                }
            });

            tableBody.addEventListener('click', e => {
                if (e.target.classList.contains('remove-btn')) {
                    const id = e.target.dataset.id;
                    delete selectedProducts[id];
                    renderTable();
                }
            });

            function updateHiddenInput() {
                hiddenInput.value = JSON.stringify(selectedProducts);
            }

            form.addEventListener('submit', e => {
                e.preventDefault();
                const rows = document.querySelectorAll('#selectedProductsTable tbody tr');
                const qty = document.querySelectorAll('.prod_quantity_input');
                const formData = new FormData(form);
                const quantity = [];

                qty.forEach(qty => {
                    console.log(qty.value);
                    formData.append('quantity[]', qty.value);

                })
                rows.forEach(row => {

                    const prodId = row.getAttribute('data-prod-id');
                    const total = row.getAttribute('data-total');

                    formData.append('product_ids[]', prodId);
                    formData.append('total[]', total);
                    // alert('s')
                });

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirectRoute;
                        } else {
                            alert('Something went wrong.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Failed to save quotation products.');
                    });
            });
        });
    </script>
@endsection
