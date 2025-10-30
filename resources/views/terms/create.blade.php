@extends('layouts.app')
@section('title', 'New Terms & conditions')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">New Terms</div>
            <div class="card-body">
                <form action="{{ route('terms.store') }}" method="POST" enctype="multipart/form-data">
                    @include('terms._form', ['submitButtonText' => 'submit'])
                </form>
            </div>
        </div>
    </div>
@endsection
