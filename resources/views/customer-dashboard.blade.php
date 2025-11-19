{{-- @extends('layouts.app')

@section('content')
    <style>
        /* Modern dark table styling */
        .quote-table-container {
            max-width: 1000px;
            margin: 0 auto;
            background: #2c2c2c;
            border-radius: 12px;
            padding: 20px;
            color: #fff;
            font-family: "Poppins", sans-serif;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .quote-table-container h3 {
            text-align: center;
            margin-bottom: 25px;
            color: #fff;
            font-weight: 600;
        }

        .quote-search-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
        }

        .quote-search-bar input {
            background: #1f1f1f;
            border: 1px solid #444;
            color: #fff;
            border-radius: 6px;
            padding: 8px 12px;
            width: 250px;
            outline: none;
            transition: all 0.2s;
        }

        .quote-search-bar input:focus {
            border-color: #0d6efd;
        }

        .quote-table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 8px;
        }

        .quote-table thead {
            background-color: #1e1e1e;
        }

        .quote-table th,
        .quote-table td {
            padding: 12px 16px;
            text-align: left;
        }

        .quote-table tbody tr {
            background-color: #343434;
            border-bottom: 1px solid #444;
            transition: background 0.2s;
        }

        .quote-table tbody tr:hover {
            background-color: #3f3f3f;
        }

        .quote-table a.btn {
            padding: 4px 10px;
            font-size: 0.85rem;
            border-radius: 6px;
        }

        /* Pagination styling */
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }

        .pagination .page-link {
            background: #1f1f1f;
            border: 1px solid #444;
            color: #fff;
            border-radius: 6px;
        }

        .pagination .page-link:hover {
            background: #0d6efd;
            border-color: #0d6efd;
        }

        .pagination .active .page-link {
            background: #0d6efd;
            border-color: #0d6efd;
        }
    </style>

    <div class="quote-table-container">
        <h3>Your Quote Requests</h3>

        <div class="quote-search-bar">
            <form action="{{ route('quote.requests') }}" method="GET">
                <input type="text" name="search" placeholder="Search by status or date..." value="{{ request('search') }}">
            </form>
        </div>

        @if ($quoteRequests->isEmpty())
            <div class="alert alert-info text-center text-dark bg-light">
                You have not submitted any quote requests yet.
            </div>
        @else
            <table class="quote-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total Products</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quoteRequests as $request)
                        <tr>
                            <td>#{{ $request->id }}</td>
                            <td>{{ $request->created_at->format('d M, Y') }}</td>
                            <td>
                                <span
                                    class="badge
                                @if ($request->status === \App\QuotationStatus::Quoted) bg-success
                                @elseif($request->status === \App\QuotationStatus::Submitted) bg-warning
                                @else bg-secondary @endif">
                                    {{ ucfirst($request->status->name) }}
                                </span>
                            </td>
                            <td>{{ $request->products->count() }}</td>
                            <td>
                                <a href="{{ route('quote.request.show', $request->id) }}"
                                     class="btn btn-outline-primary btn-sm">View</a>
                                @if ($request->status === \App\QuotationStatus::Quoted)
                                    <a href="{{ route('quotations.download-pdf', $request->id) }}"
                                        class="btn btn-success btn-sm">Download</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $quoteRequests->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection --}}
@extends('layouts.app')

@section('content')
    <a class="btn btn-outline-info" href="{{ route('quote.request.create') }}">Request a quote</a>

    @livewire('customer.quote-requests-table')
@endsection
