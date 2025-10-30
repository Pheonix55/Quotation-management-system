@extends('layouts.app')
@section('title', 'New Customer')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">New Customer</div>
            <div class="card-body">
                <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
                    @include('customer._form', ['submitButtonText' => 'Create Product'])
                </form>
            </div>
        </div>
    </div>
@endsection
