@extends('layouts.app')

@section('content')
    <div class="container py-5 text-center">
        <div class="card shadow-sm mx-auto" style="max-width: 500px; border-radius: 1rem;">
            <div class="card-body">
                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                </div>
                <h3 class="card-title mb-3">Quote Request Submitted!</h3>
                <p class="card-text mb-4">
                    Your quote request has been successfully submitted. Our team will review it and get back to you shortly.
                </p>
                <a href="{{ route('quote.request.create') }}" class="btn btn-primary">
                    Submit Another Quote Request
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary ms-2">
                    Go to Dashboard
                </a>
            </div>
        </div>
    </div>
@endsection
