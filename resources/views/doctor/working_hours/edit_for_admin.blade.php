@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3>Edit Working Hours — Dr. {{ $doctor->name }}</h3>

    <form method="POST" action="{{ route('admin.doctors.working-hours.store', $doctor->id) }}">
        @csrf

        <p class="text-muted">Add intervals as weekday (0=Sun .. 6=Sat), start_time and end_time (HH:MM).</p>

        <div class="mb-3">
            <label class="form-label">Intervals (JSON)</label>
            <textarea name="intervals" class="form-control" rows="6">[ { "weekday": 1, "start_time": "09:00", "end_time": "12:00" } ]</textarea>
            <div class="form-text">For admin convenience you can paste a small JSON array. This form will be improved later to a visual editor.</div>
        </div>

        <button class="btn btn-primary">Save</button>
        <a href="{{ route('admin.doctors.show', $doctor->id) }}" class="btn btn-secondary ms-2">Back</a>
    </form>
</div>

@endsection
