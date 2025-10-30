@extends('layouts.app')
@section('title', 'Edit Customer')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">Edit Customer</div>
            <div class="card-body">
                <form action="{{ route('customer.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    @include('customer._form', ['submitButtonText' => 'Update Customer'])
                </form>
            </div>
        </div>
    </div>
@endsection
