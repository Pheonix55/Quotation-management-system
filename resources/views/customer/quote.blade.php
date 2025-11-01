@extends('layouts.app')

@section('content')
    <style>
        .hidden {
            display: none !important;
        }
    </style>
    <div class="container">
        <h3>Request a Quotation</h3>

        {{-- Check if there are any products --}}
        @if ($products == null)
            <div class="alert alert-warning">
                No products found. Please create one to continue.
            </div>

            {{-- Inline product creation form --}}
            <form action="{{ route('product.store') }}" method="POST" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Select Category --</option>

                            @foreach ($category as $cat)
                                <option value="{{ $cat->id }}">
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sale Price ($)</label>
                        <input type="number" step="0.01" name="sale_price" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cost Price ($)</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">GST %</label>
                        <input type="number" step="0.01" name="gst" class="form-control" required>
                    </div>


                    <div class="col-md-6 mb-3">
                        <label class="form-label">BarCode</label>
                        <input type="text" name="bar_code" class="form-control" id="barcode">
                    </div>
                    <div class="col-md-6 mb-3 hidden">
                        <input type="text" name="redirect_route" class="form-control" id="barcode"
                            value="{{ route('quote.request.create') }}">
                    </div>


                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success">Create Product</button>
                    </div>
                </div>


            </form>
        @else
            <form action="{{ route('quote.request.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Select Products</label>
                    <select name="product_ids[]" multiple class="form-control">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Rs. {{ $product->sale_price }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Additional Terms (optional)</label>
                    <textarea name="terms" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit Request</button>
            </form>
        @endif
    </div>
    <script>
        const barcodeInput = document.getElementById('barcode');

        barcodeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    </script>
@endsection
