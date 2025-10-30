@extends('layouts.app')
@section('title', 'Edit Terms')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">Edit Terms</div>
            <div class="card-body">
                <form action="{{ route('terms.update', $terms->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    @include('terms._form', ['submitButtonText' => 'Update terms'])
                </form>
            </div>
        </div>
    </div>
@endsection
