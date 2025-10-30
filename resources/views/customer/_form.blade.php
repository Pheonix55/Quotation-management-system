@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">email</label>
        <input type="email" name="email" class="form-control" value="{{ old('price', $customer->email ?? '') }}"
            required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Assign password</label>
        <input type="password" name="password" class="form-control" value="{{ old('', $customer->password ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control"
            value="{{ old('', $customer->password_confirmation ?? '') }}">
    </div>


    <div class="col-12 text-end">
        <button type="submit" class="btn btn-primary px-4">{{ $submitButtonText }}</button>
    </div>
</div>
