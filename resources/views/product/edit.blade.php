@extends('layouts.app')
@section('title', 'Edit Product')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">Edit Product</div>
            <div class="card-body">
                <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    @include('product._form', ['submitButtonText' => 'Update Product'])
                </form>
            </div>
        </div>
    </div>
@endsection
