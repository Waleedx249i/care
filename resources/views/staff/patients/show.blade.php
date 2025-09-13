@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Patient: {{ $patient->name }} ({{ $patient->code }})</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3"><div class="card-body">
                <p><strong>Phone:</strong> {{ $patient->phone }}</p>
                <p><strong>Gender:</strong> {{ ucfirst($patient->gender) }}</p>
                <p><strong>Birth Date:</strong> {{ $patient->birth_date?->format('Y-m-d') }}</p>
                <p><strong>Address:</strong><br>{{ $patient->address }}</p>
            </div></div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3"><div class="card-body">
                <p><strong>Notes</strong><br>{{ $patient->notes }}</p>
            </div></div>
        </div>
    </div>
    <a href="{{ route('staff.patients.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
