@extends('layouts.app')
@section('title', 'New Category')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">New Category</div>
            <div class="card-body">
                <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                    @include('category._form', ['submitButtonText' => 'submit'])
                </form>
            </div>
        </div>
    </div>
@endsection
