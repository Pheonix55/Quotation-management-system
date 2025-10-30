@extends('layouts.app')
@section('content')
    <div class="card" style="width: 18rem;">
        <a href="{{ route('quote') }}">
            <div class="card-body">
                <h5 class="card-title">Get a Quote</h5>
                <h6 class="card-subtitle mb-2 text-muted">Quotation</h6>
                <p class="card-text">just dd your product and terms nd get yourself a quote</p>
                <a href="#" class="card-link"></a>
            </div>
        </a>
    </div>
@endsection
