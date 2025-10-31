@extends('layouts.app')
@section('title', 'Customers')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">Category</h4>
            <a href="{{ route('category.create') }}" class="btn btn-primary">+ New Category</a>
        </div>

        <div class="card shadow-sm border-0 bg-theme">
            <div class="card-body">
                <table id="productsTable" class="table table-striped table-hover align-middle dark-table">
                    <thead class="">
                        <tr>
                            <th>#</th>
                            <th>name</th>
                            <th>actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($terms))

                            <td>no Category yet!</td>
                        @else
                            @foreach ($terms as $term)
                                <tr>
                                    {{-- @dd($term) --}}
                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $term->name }}</td>

                                    <td>
                                        <a href="{{ route('category.edit', $term->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('category.destroy') }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <input type="number" value="{{ $term->id }}" name="id"
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
