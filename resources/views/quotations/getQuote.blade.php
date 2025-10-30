@extends('layouts.app')

@section('content')
    <div class="card" style="width: 100%;">
        <form action="{{ route('quote.store') }}" method="POST" enctype="multipart/form-data" id="quoteForm">
            @csrf
            <div class="row p-3">

                <div class="col-md-6 mb-3">
                    <label for="customers" class="form-label">Select Customers</label>
                    <select name="customer_id" id="customers" class="form-select"required>
                        <option disabled selected>Select</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="col-md-6 mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" name="quotation_date" id="date" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="time" class="form-label">Time</label>
                    <input type="time" class="form-control" name="quotation_time" id="time" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="validity_date" class="form-label">Validity Date</label>
                    <input type="date" class="form-control" name="validity_date" id="validity_date" required>
                </div>

                <div class="col-12 mb-3">
                    <label for="notes" class="form-label">Notes (optional)</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Add any notes if necessary..."></textarea>
                </div>

                <input type="hidden" name="selected_customers" id="selected_customers">

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary px-4">Done</button>
                </div>
            </div>
        </form>
    </div>


    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownMenu = document.querySelector('#prodDropdown');
            const checkboxes = dropdownMenu.querySelectorAll('.form-check-input');
            const hiddenInput = document.querySelector('#selected_customers');
            const form = document.querySelector('#quoteForm');

            dropdownMenu.addEventListener('click', function(e) {
                if (e.target.classList.contains('form-check-input')) {
                    e.stopPropagation();
                }
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const selectedIds = Array.from(checkboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.value);
                    hiddenInput.value = JSON.stringify(selectedIds);
                });
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                formData.append('selected_customers', hiddenInput.value);
                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Redirecting to:', data.redirectRoute);
                            window.location.href = data.redirectRoute;
                        } else if (data.errors) {
                            console.error('Validation errors:', data.errors);
                            alert('Please fix validation errors and try again.');
                        } else {
                            alert(data.message || 'Something went wrong');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to create quotation.');
                    });
            });
        });
    </script> --}}
@endsection

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownMenuTerm = document.querySelector('#termDropdown');

        dropdownMenuTerm.addEventListener('click', function(e) {
            if (e.target.classList.contains('form-check-input')) {
                e.stopPropagation();
            }
        });

        const dropdownToggle = document.querySelector('#drop_down_toggle_terms');
        const checkboxesTerm = dropdownMenuTerm.querySelectorAll('.form-check-input');
        checkboxesTerm.forEach(
            checkbox => {
                checkbox.addEventListener('change', function() {
                    const selectedTerms = Array.from(checkboxesTerm)
                        .filter(cb => cb.checked)
                        .map(cb => cb.nextElementSibling.getAttribute('data-id'));
                    console.log(selectedTerms);

                    console.log(selectedTerms);
                });
            });
    });
</script> --}}
{{-- <div class="col-md-6 mb-3">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="true" onclick="openDropdown()" id="drop_down_toggle_terms">
                                Select terms
                            </button>
                            <ul class="dropdown-menu" id="termDropdown">
                                @foreach ($terms as $term)
                                    <li>
                                        <div class="form-check dropdown-item">
                                            <input class="form-check-input" type="checkbox" value="{{ $term->id }}"
                                                id="option1">
                                            <label class="form-check-label" for="option1" data-id="{{ $product->id }}">
                                                {{ $term->statements }}
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div> --}}
