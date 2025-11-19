@extends('layouts.app')

@section('content')
    <div class="container-fluid py-5">
        <div class="row">
            {{-- Left Column: Incomplete Quotations --}}
            <div class="col-lg-8 col-md-12 mb-4">
                <h3 class="mb-4">Incomplete Quotations</h3>
                <div class="row g-4">
                    @if ($quotations_not_complete->isEmpty())
                        <p class="text-muted">No incomplete quotations.</p>
                    @else
                        @foreach ($quotations_not_complete as $quotation)
                            @if ($quotation->status == 0)
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="card quote-card h-100">
                                        <a href="{{ route('quotation.addProducts', $quotation->id) }}">
                                            <div class="card-body text-center">
                                                <h5 class="card-title">Quotation #{{ $quotation->id }}</h5>
                                                <p class="card-text mb-1"><strong>Date:</strong>
                                                    {{ $quotation->quotation_date }}</p>
                                                <p class="card-text mb-1"><strong>Customer:</strong>
                                                    {{ $quotation->customer->name ?? 'N/A' }}</p>
                                                <p class="card-text mb-1"><strong>Validity:</strong>
                                                    {{ $quotation->validity_date }}</p>
                                                <span class="badge bg-warning text-dark">Incomplete</span>
                                                <span class="btn btn-primary mt-2">Continue</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Right Column: New Quote Requests --}}
            <div class="col-lg-4 col-md-12">
                <h3 class="mb-4">New Quote Requests</h3>
                @if ($quoteRequests->isEmpty())
                    <p class="text-muted">No new quote requests.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quoteRequests as $quote)
                                    <tr>
                                        <td>{{ $quote->id }}</td>
                                        <td>{{ $quote->created_at->format('d M, Y') }}</td>
                                        <td>{{ $quote->user->name ?? 'N/A' }}</td>
                                        <td>{{ $quote->products->count() }}</td>
                                        <td>
                                            <span
                                                class="badge
                                            @if ($quote->status === \App\QuotationStatus::Draft) bg-secondary
                                            @elseif($quote->status === \App\QuotationStatus::Submitted) bg-info
                                            @elseif($quote->status === \App\QuotationStatus::Reviewed) bg-primary
                                            @else bg-success @endif">
                                                {{ ucfirst($quote->status->value) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('quote.request.show', $quote->id) }}"
                                                class="btn btn-sm btn-primary">View</a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    <div class="mt-3 paginationContainer">
                        {{ $quoteRequests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Optional card styling --}}
    <style>
        .paginationContainer {
            svg {
                height: 50px;
            }
        }

        .quote-card {
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
            text-decoration-line: none;
        }
    </style>
@endsection
