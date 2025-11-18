@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="card shadow-sm p-4">
            <h2 class="mb-4">Quote Request #{{ $quote->id }}</h2>

            {{-- User Info --}}
            <div class="mb-3">
                <h5>Customer Info</h5>
                <p><strong>Name:</strong> {{ $quote->user->name }}</p>
                <p><strong>Email:</strong> {{ $quote->user->email }}</p>
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <h5>Status</h5>
                <span
                    class="badge
                @if ($quote->status === \App\QuotationStatus::Quoted) bg-success
                @elseif($quote->status === \App\QuotationStatus::Submitted) bg-info
                @elseif($quote->status === \App\QuotationStatus::Reviewed) bg-primary
                @else bg-secondary @endif">
                    {{ ucfirst($quote->status->value) }}
                </span>
            </div>

            {{-- Notes --}}
            @if ($quote->notes)
                <div class="mb-3">
                    <h5>Notes</h5>
                    <p>{{ $quote->notes }}</p>
                </div>
            @endif

            {{-- Products --}}
            <div class="mb-3">
                <h5>Products</h5>
                @if ($quote->products->isEmpty())
                    <p>No products added.</p>
                @else
                    <ul class="list-group">
                        @foreach ($quote->products as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $product->name }}
                                <span class="badge bg-dark">{{ $product->pivot->created_at->format('d M, Y') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Actions --}}
            <div class="mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Quotes</a>
                @if ($quote->status === \App\QuotationStatus::Quoted)
                    <a href="{{ route('quotations.download-pdf', $quote->id) }}" class="btn btn-success">Download PDF</a>
                @endif
            </div>
        </div>
    </div>
@endsection
