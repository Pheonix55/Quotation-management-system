@extends('layouts.app')
@section('title', 'Customers')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">Customers</h4>
            <a href="{{ route('customer.create') }}" class="btn btn-primary">+ New Customer</a>
        </div>

        <div class="card shadow-sm border-0 bg-theme">
            <div class="card-body">
                <table id="productsTable" class="table table-striped table-hover align-middle dark-table">
                    <thead class="">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($customers))

                            <td>no Customers yet!</td>
                        @else
                            @foreach ($customers as $customer)
                                <tr>
                                    {{-- @dd($customer) --}}
                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email ?? '—' }}</td>

                                    <td>
                                        <a href="{{ route('customer.edit', $customer->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('customer.destroy') }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <input type="number" value="{{ $customer->id }}" name="id"
                                                style="display: none">
                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this product?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>



@endsection
