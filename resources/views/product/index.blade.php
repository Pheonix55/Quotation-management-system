@extends('layouts.app')
@section('title', 'Products')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">Products</h4>
            <a href="{{ route('product.create') }}" class="btn btn-primary">+ Add Product</a>
        </div>

        <div class="card shadow-sm border-0 bg-theme">
            <div class="card-body">
                <table id="productsTable" class="table table-striped table-hover align-middle dark-table">
                    <thead class="">
                        <tr>
                            <th>#</th>
                            <th>barCode</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Sale Price</th>
                            <th>Cost Price</th>
                            <th>GST %</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($products))

                            <td>no products yet!</td>
                        @else
                            @foreach ($products as $product)
                                <tr>
                                    {{-- @dd($product) --}}
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->bar_code }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category_id ?? '—' }}</td>
                                    <td>${{ number_format($product->sale_price, 2) }}</td>
                                    <td>${{ number_format($product->cost_price, 2) }}</td>
                                    <td>${{ number_format($product->gst, 2) }}</td>

                                    <td>
                                        <a href="{{ route('product.edit', $product->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('product.destroy') }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <input type="number" value="{{ $product->id }}" name="id"
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
