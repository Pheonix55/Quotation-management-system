@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Category</label>
        <input type="text" name="name" class="form-control" value="{{ old('statements', $terms->statements ?? '') }}"
            required>
    </div>

    <div class="col-12 text-end">
        <button type="submit" class="btn btn-primary px-4">{{ $submitButtonText }}</button>
    </div>
</div>
