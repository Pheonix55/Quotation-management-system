@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Select Terms & Conditions for Quotation #{{ $quotation->id }}</h4>

        <form id="termsForm" action="{{ route('quotations.storeTerms', $quotation->id) }}" method="POST">
            @csrf


            <div class="mb-3">
                @forelse($terms as $term)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="term_ids[]" value="{{ $term->id }}"
                            id="term_{{ $term->id }}">
                        <label class="form-check-label" for="term_{{ $term->id }}">
                            {{ $term->statements }}
                        </label>
                    </div>
                @empty
                    <p>No terms available for this customer.</p>
                @endforelse
                <div class="mt-4">
                    <label class="form-label fw-bold">Add Custom Terms</label>
                    <div id="customTermsContainer"></div>
                    <button type="button" id="addCustomTermBtn" class="btn btn-outline-secondary btn-sm mt-2">
                        + Add Custom Term
                    </button>
                </div>
            </div>


            <button type="button" id="confirmSelectionBtn" class="btn btn-primary">
                Review & Confirm Terms
            </button>
        </form>
    </div>

    <div class="modal fade" id="confirmTermsModal" tabindex="-1" aria-labelledby="confirmTermsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmTermsLabel">Review and Edit Terms for Quotation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="selectedTermsContainer"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="saveConfirmedTerms" class="btn btn-success">Save to Quotation</button>
                </div>
            </div>
        </div>
    </div>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            const confirmBtn = document.getElementById('confirmSelectionBtn');
            const modal = new bootstrap.Modal(document.getElementById('confirmTermsModal'));
            const selectedContainer = document.getElementById('selectedTermsContainer');
            const saveBtn = document.getElementById('saveConfirmedTerms');
            const form = document.getElementById('termsForm');

            confirmBtn.addEventListener('click', () => {
                selectedContainer.innerHTML = '';
                const selected = Array.from(document.querySelectorAll('input[name="term_ids[]"]:checked'));

                if (selected.length === 0) {
                    alert('Please select at least one term.');
                    return;
                }

                selected.forEach(input => {
                    const label = document.querySelector(`label[for="${input.id}"]`).innerText;
                    const wrapper = document.createElement('div');
                    wrapper.classList.add('mb-3');
                    wrapper.innerHTML = `
                <label class="form-label fw-bold">Term #${input.value}</label>
                <textarea class="form-control" name="custom_terms[${input.value}]" rows="2">${label}</textarea>
            `;
                    selectedContainer.appendChild(wrapper);
                });

                modal.show();
            });

            saveBtn.addEventListener('click', () => {
                modal.hide();

                const formData = new FormData(form);

                const customTextareas = selectedContainer.querySelectorAll('textarea');
                customTextareas.forEach(t => formData.append(t.name, t.value));

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(async res => {
                        const text = await res.text();
                        try {
                            const data = JSON.parse(text);
                            if (data.success) {
                                alert(data.message);
                                window.location.href = data.redirectRoute;
                            } else {
                                alert(data.message || 'Something went wrong.');
                            }
                        } catch (e) {
                            console.error('Invalid JSON:', text);
                            alert('Server returned invalid response.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Failed to save quotation terms.');
                    });
            });
        });
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const confirmBtn = document.getElementById('confirmSelectionBtn');
            const modal = new bootstrap.Modal(document.getElementById('confirmTermsModal'));
            const selectedContainer = document.getElementById('selectedTermsContainer');
            const saveBtn = document.getElementById('saveConfirmedTerms');
            const form = document.getElementById('termsForm');
            const addCustomBtn = document.getElementById('addCustomTermBtn');
            const customContainer = document.getElementById('customTermsContainer');

            // Add custom term dynamically
            addCustomBtn.addEventListener('click', () => {
                const index = customContainer.children.length + 1;
                const wrapper = document.createElement('div');
                wrapper.classList.add('mb-2');
                wrapper.innerHTML = `
            <div class="input-group">
                <textarea name="custom_terms_new[]" class="form-control" rows="2" placeholder="Enter custom term #${index}"></textarea>
                <button type="button" class="btn btn-danger removeCustomBtn">&times;</button>
            </div>
        `;
                customContainer.appendChild(wrapper);
            });

            // Remove a custom term
            customContainer.addEventListener('click', e => {
                if (e.target.classList.contains('removeCustomBtn')) {
                    e.target.closest('.mb-2').remove();
                }
            });

            // Confirm selected terms (existing + custom)
            confirmBtn.addEventListener('click', () => {
                selectedContainer.innerHTML = '';
                const selected = Array.from(document.querySelectorAll('input[name="term_ids[]"]:checked'));
                const customNew = Array.from(document.querySelectorAll(
                    'textarea[name="custom_terms_new[]"]'));

                if (selected.length === 0 && customNew.length === 0) {
                    alert('Please select or add at least one term.');
                    return;
                }

                // Existing selected terms
                selected.forEach(input => {
                    const label = document.querySelector(`label[for="${input.id}"]`).innerText;
                    const wrapper = document.createElement('div');
                    wrapper.classList.add('mb-3');
                    wrapper.innerHTML = `
                <label class="form-label fw-bold">Existing Term #${input.value}</label>
                <textarea class="form-control" name="custom_terms[${input.value}]" rows="2">${label}</textarea>
            `;
                    selectedContainer.appendChild(wrapper);
                });

                // Custom newly added terms
                customNew.forEach((textarea, idx) => {
                    const wrapper = document.createElement('div');
                    wrapper.classList.add('mb-3');
                    wrapper.innerHTML = `
                <label class="form-label fw-bold">Custom Term #${idx + 1}</label>
                <textarea class="form-control" name="custom_terms_new[]" rows="2">${textarea.value}</textarea>
            `;
                    selectedContainer.appendChild(wrapper);
                });

                modal.show();
            });

            saveBtn.addEventListener('click', () => {
                modal.hide();

                const formData = new FormData(form);

                // Append all edited existing terms
                selectedContainer.querySelectorAll('textarea[name^="custom_terms["]').forEach(t => {
                    formData.append(t.name, t.value);
                });

                // Append newly added custom terms
                // selectedContainer.querySelectorAll('textarea[name="custom_terms_new[]"]').forEach(t => {
                //     formData.append('custom_terms_new[]', t.value);
                customContainer.querySelectorAll('.custom-term-textarea').forEach(t => {
                    formData.append('custom_terms_new[]', t.value);

                });

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(async res => {
                        const text = await res.text();
                        try {
                            const data = JSON.parse(text);
                            if (data.success) {
                                alert(data.message);
                                window.location.href = data.redirectRoute;
                            } else {
                                alert(data.message || 'Something went wrong.');
                            }
                        } catch (e) {
                            console.error('Invalid JSON:', text);
                            alert('Server returned invalid response.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Failed to save quotation terms.');
                    });
            });

        });
    </script>
@endsection
