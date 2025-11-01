<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Optional: Bootstrap 5 theme -->
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tableBody = document.querySelector('#selectedProductsTable tbody');
        const hiddenInput = document.getElementById('selected_products');
        const form = document.getElementById('addProductsForm');
        let selectedProducts = {};

        $('#productSelect').select2({
            theme: 'bootstrap-5',
            placeholder: 'Search product...',
            ajax: {
                url: '{{ route('products.search') }}',
                dataType: 'json',
                delay: 500, 
                data: params => ({
                    q: params.term
                }),
                processResults: data => ({
                    results: data.map(p => ({
                        id: p.id,
                        text: `${p.name} (Rs. ${p.sale_price})`,
                        price: p.sale_price,
                        gst: p.gst
                    }))
                }),
                cache: true
            },
            minimumInputLength: 2
        });

        $('#productSelect').on('select2:select', function(e) {
            const prod = e.params.data;
            if (!selectedProducts[prod.id]) {
                selectedProducts[prod.id] = {
                    name: prod.text,
                    quantity: 1,
                    price: prod.price,
                    gst: prod.gst,
                    total: 0
                };
                addRow(prod.id, selectedProducts[prod.id]);
                updateHiddenInput();
            }

            // clear input for next product
            $(this).val(null).trigger('change');
        });

        // ✅ Add a new row directly without re-rendering entire table
        function addRow(id, prod) {
            const row = document.createElement('tr');
            row.dataset.prodId = id;
            row.dataset.total = (prod.price * prod.quantity).toFixed(2);
            row.innerHTML = `
            <td>${prod.name}</td>
            <td><input type="number" min="1" value="${prod.quantity}" class="form-control form-control-sm qty-input" data-id="${id}"></td>
            <td><input type="number" min="0"
                       name="prod_price"
                       value="${prod.price.toFixed(2)}"
                       class="form-control form-control-sm prod_price_input"
                       data-id="${id}"></td>
            <td>${prod.gst}%</td>
            <td>${(prod.price * prod.quantity).toFixed(2)}</td>
            <td><button class="btn btn-sm btn-danger remove-btn" data-id="${id}">×</button></td>
        `;
            tableBody.appendChild(row);
        }

        // ✅ Quantity change
        tableBody.addEventListener('input', e => {
            if (e.target.classList.contains('qty-input')) {
                const id = e.target.dataset.id;
                const qty = parseInt(e.target.value);
                if (qty > 0 && selectedProducts[id]) {
                    selectedProducts[id].quantity = qty;
                    const row = e.target.closest('tr');
                    const newTotal = (selectedProducts[id].price * qty).toFixed(2);
                    row.dataset.total = newTotal;
                    row.children[4].textContent = newTotal;
                    updateHiddenInput();
                }
            }
        });

        // ✅ Remove row without re-render
        tableBody.addEventListener('click', e => {
            if (e.target.classList.contains('remove-btn')) {
                const id = e.target.dataset.id;
                delete selectedProducts[id];
                e.target.closest('tr').remove();
                updateHiddenInput();
            }
        });

        // ✅ Hidden input update
        function updateHiddenInput() {
            hiddenInput.value = JSON.stringify(selectedProducts);
        }

        // ✅ Form submission
        form.addEventListener('submit', e => {
            e.preventDefault();

            const formData = new FormData(form);
            Object.entries(selectedProducts).forEach(([id, prod]) => {
                formData.append('product_ids[]', id);
                formData.append('quantity[]', prod.quantity);
                formData.append('total[]', (prod.price * prod.quantity).toFixed(2));
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
