@extends('layouts.auth')

@section('content')
    <div class="auth-wrapper d-flex justify-content-center align-items-center min-vh-100 bg-light">
        <div class="card shadow-sm p-4" style="width: 25rem;">
            <h4 class="mb-4 text-center">Login</h4>

            {{-- Global error alert --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Whoops!</strong> Please fix the errors below.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Success message --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('loginPost') }}" method="POST" novalidate>
                @csrf

                {{-- Email Field --}}
                <div class="mb-3">
                    <label for="Email" class="form-label">Email address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="Email"
                        name="email" value="{{ old('email') }}" aria-describedby="emailHelp" required>
                    <div id="emailHelp" class="form-text">
                        We'll never share your email with anyone else.
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div class="mb-3">
                    <label for="Password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="Password"
                        name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Additional Info --}}
                <div class="d-flex justify-content-between mb-3">
                    <a href="{{ route('registerView') }}" class="small text-decoration-none">New User?</a>
                    <a href="{{ route('forget-password') }}" class="small text-decoration-none">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
@endsection
