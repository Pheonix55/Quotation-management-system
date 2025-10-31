@extends('layouts.general')

@section('content')
    <style>
        .wrapper {
            width: 80%;
            margin: 100 auto;

        }
    </style>
    <div class="card wrapper">
        <form action="{{ route('quote.store') }}" method="POST" enctype="multipart/form-data" id="quoteForm" novalidate>
            @csrf
            <div class="row p-3 ">

                {{-- Customer --}}
                <div class="col-md-6 mb-3">
                    <label for="customers" class="form-label">Select Customer</label>
                    <select name="customer_id" id="customers" class="form-select @error('customer_id') is-invalid @enderror"
                        required>
                        <option disabled selected>Select</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Date --}}
                <div class="col-md-6 mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control @error('quotation_date') is-invalid @enderror"
                        name="quotation_date" id="date" required
                        value="{{ old('quotation_date') ?? now()->format('Y-m-d') }}">
                    @error('quotation_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Time --}}
                <div class="col-md-6 mb-3">
                    <label for="time" class="form-label">Time</label>
                    <input type="time" class="form-control @error('quotation_time') is-invalid @enderror"
                        name="quotation_time" id="time" required
                        value="{{ old('quotation_time') ?? now()->format('H:i') }}">
                    @error('quotation_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="validity_date" class="form-label">Validity Date</label>
                    <input type="date" class="form-control @error('validity_date') is-invalid @enderror"
                        name="validity_date" id="validity_date" required value="{{ old('validity_date') }}">
                    @error('validity_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="notes" class="form-label">Notes (optional)</label>
                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3"
                        placeholder="Add any notes if necessary...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <input type="hidden" name="selected_customers" id="selected_customers">

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary px-4">Done</button>
                </div>
            </div>
        </form>
    </div>
