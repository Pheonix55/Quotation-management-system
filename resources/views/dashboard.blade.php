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

        .wrapper_cards {
            display: flex;
            align-items: stretch;
        }
    </style>

    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show text-center mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show text-center mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @admin
            <div class="row g-4 justify-content-center wrapper_cards">
                <div class="col-md-4 col-sm-6">
                    <div class="quote-card h-100">
                        <a href="{{ route('quote') }}">
                            <div class="card-body text-center">
                                <h5 class="card-title">Get a Quote</h5>
                                <p class="card-text">Add your products and terms to generate a customized quotation instantly.
                                </p>
                                <span class="quote-btn">Create Quotation</span>
                            </div>
                        </a>
                    </div>
                </div>

                @foreach ($quotations_not_complete as $quotation)
                    @if ($quotation->status == 0)
                        <div class="col-md-4 col-sm-6">
                            <div class="quote-card h-100">
                                <a href="{{ route('quotation.addProducts', $quotation->id) }}">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Quotation #{{ $quotation->id }}</h5>
                                        <p class="card-text mb-1"><strong>Date:</strong> {{ $quotation->quotation_date }}</p>
                                        <p class="card-text mb-1"><strong>Customer:</strong>
                                            {{ $quotation->customer->name ?? 'N/A' }}</p>
                                        <p class="card-text mb-1"><strong>Validity:</strong> {{ $quotation->validity_date }}</p>
                                        <p class="card-text">
                                            <span class="badge bg-warning text-dark">Incomplete</span>
                                        </p>
                                        <span class="quote-btn">Continue</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endadmin
        @customer
            <div class="row g-4 justify-content-center wrapper_cards">
                <div class="col-md-4 col-sm-6">
                    <div class="quote-card h-100">
                        <a href="{{ route('quote.request.create') }}">
                            <div class="card-body text-center">
                                <h5 class="card-title">Request a Quote</h5>
                                <p class="card-text">Add your products and terms to generate a customized quotation instantly.
                                </p>
                                <span class="quote-btn">Create Quotation request</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        @endcustomer
    </div>
@endsection
