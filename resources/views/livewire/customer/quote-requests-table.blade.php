<div class="quote-table-container">
    <h3>Your Quote Requests</h3>

    <div class="quote-toolbar">
        <div class="quote-search">
            {{-- <input type="text" wire:model.debounce.300ms="search" placeholder="Search by status or date..."> --}}
            <input type="text" wire:model.live="search" placeholder="Search by status or date...">

        </div>

        <div class="quote-filter">
            <select wire:model.live="statusFilter" class="form-select">
                <option value="">All Statuses</option>
                <option value="draft">Draft</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="submitted">submitted</option>
            </select>

        </div>
    </div>

    @if ($quoteRequests->isEmpty())
        <div class="alert alert-info text-center text-dark bg-light">
            You have not submitted any quote requests yet.
        </div>
    @else
        <table class="quote-table">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')">#
                        @if ($sortField === 'id')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('created_at')">Date
                        @if ($sortField === 'created_at')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('status')">Status
                        @if ($sortField === 'status')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th>Products</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quoteRequests as $request)
                    <tr wire:key="quote-{{ $request->id }}">
                        <td>#{{ $request->id }}</td>
                        <td>{{ $request->created_at->format('d M, Y') }}</td>
                        <td>
                            <span
                                class="badge
                                @if ($request->status === \App\QuotationStatus::Quoted) bg-success
                                @elseif($request->status === \App\QuotationStatus::Submitted) bg-info
                                @elseif($request->status === \App\QuotationStatus::Reviewed) bg-primary
                                @else bg-secondary @endif">
                                {{ ucfirst($request->status->value) }}
                            </span>
                        </td>
                        <td>
                            @foreach ($request->products as $product)
                                <span class="badge bg-dark">{{ $product->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('quote.request.show', $request->id) }}"
                                class="btn btn-outline-dark btn-sm">View</a>
                            @if ($request->status != \App\QuotationStatus::Quoted)
                                <a href="{{ route('quotations.edit', $request->id) }}"
                                    class="btn btn-outline-success btn-sm">edit</a>
                            @elseif ($request->status === \App\QuotationStatus::Quoted)
                                <a href="{{ route('quotations.download-pdf', $request->id) }}"
                                    class="btn btn-success btn-sm">Download</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3 ">
            {{ $quoteRequests->links() }}
        </div>
    @endif

    <style>
        .quote-table-container {
            max-width: 1000px;
            margin: 0 auto;
            color: #2c2c2c;
            border-radius: 12px;
            padding: 20px;
            background: #fff;
            font-family: "Poppins", sans-serif;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .quote-table-container h3 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c2c2c;
            font-weight: 600;
        }

        .quote-toolbar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            gap: 10px;
        }

        .quote-search input,
        .quote-filter select {
            background: #fff;
            border: 1px solid #444;
            color: #2c2c2c;
            border-radius: 6px;
            padding: 8px 12px;
            outline: none;
            transition: all 0.2s;
        }

        .quote-search input:focus,
        .quote-filter select:focus {
            border-color: #0d6efd;
        }

        .quote-table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 8px;
        }

        .quote-table thead {
            background-color: #ccc;
            cursor: pointer;
        }

        .quote-table th,
        .quote-table td {
            padding: 12px 16px;
            text-align: left;
        }

        .quote-table tbody tr {
            background-color: #fff;
            border-bottom: 1px solid #444;
            transition: background 0.2s;
        }

        .quote-table tbody tr:hover {
            background-color: #3f3f3f;
            color: #ccc;
        }

        .quote-table a.btn {
            padding: 4px 10px;
            font-size: 0.85rem;
            border-radius: 6px;
        }

        .pagination {
            justify-content: center;
            margin-top: 20px;
        }

        .pagination .page-link {
            background: #fff;
            border: 1px solid #444;
            color: #2c2c2c;
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
</div>
