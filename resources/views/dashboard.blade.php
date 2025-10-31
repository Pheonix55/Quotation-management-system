@extends('layouts.app')

@section('content')
    <style>
        .quote-card {
            width: 100%;
            max-width: 320px;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            background: #fff;
        }

        .quote-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .quote-card a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .quote-card .card-body {
            padding: 1.5rem;
        }

        .quote-card h5 {
            font-weight: 600;
            color: #2b2b2b;
        }

        .quote-card h6 {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 0.75rem;
        }

        .quote-card p {
            font-size: 0.9rem;
            color: #555;
        }

        .quote-btn {
            display: inline-block;
            margin-top: 1rem;
            background-color: #007bff;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background 0.2s;
        }

        .quote-btn:hover {
            background-color: #0056b3;
            color: #fff;
        }

        .card-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
        }
    </style>

    <div class="container card-container">
        <div class="quote-card">
            <a href="{{ route('quote') }}">
                <div class="card-body text-center">
                    <h5 class="card-title">Get a Quote</h5>
                    {{-- <h6 class="card-subtitle mb-2 text-muted">Quotation</h6> --}}
                    <p class="card-text">
                        Add your products and terms to generate a customized quotation instantly.
                    </p>
                    <span class="quote-btn">Create Quotation</span>
                </div>
            </a>
        </div>
    </div>
@endsection
