@extends('layouts.app')
@section('title', 'Add Product')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">Add Product</div>
            <div class="card-body">
                <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                    @include('product._form', ['submitButtonText' => 'Create Product'])
                </form>
            </div>
        </div>
    </div>
@endsection
