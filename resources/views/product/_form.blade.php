@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select" required>
            <option value="">-- Select Category --</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            {{-- @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach --}}
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Sale Price ($)</label>
        <input type="number" step="0.01" name="sale_price" class="form-control"
            value="{{ old('price', $product->sale_price ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Cost Price ($)</label>
        <input type="number" step="0.01" name="cost_price" class="form-control"
            value="{{ old('cost_price', $product->cost_price ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">GST %</label>
        <input type="number" step="0.01" name="gst" class="form-control"
            value="{{ old('gst', $product->gst ?? '') }}" required>
    </div>


    <div class="col-md-6 mb-3">
        <label class="form-label">BarCode</label>
        <input type="text" name="bar_code" class="form-control" id="barcode">
    </div>

    <div class="col-12 text-end">
        <button type="submit" class="btn btn-primary px-4">{{ $submitButtonText }}</button>
    </div>
</div>
<script>
    const barcodeInput = document.getElementById('barcode');

    barcodeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
</script>
