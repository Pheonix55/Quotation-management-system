@extends('layouts.app')
@section('title', 'Edit Category')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">Edit Category</div>
            <div class="card-body">
                <form action="{{ route('category.update', $terms->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    @include('category._form', ['submitButtonText' => 'Update category'])
                </form>
            </div>
        </div>
    </div>
@endsection
