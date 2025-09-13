@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mt-4">
        <div class="card-header bg-warning text-dark">تعديل موعد</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="starts_at" class="form-label">من</label>
                    <input type="datetime-local" name="starts_at" id="starts_at" class="form-control" value="{{ $appointment->starts_at->format('Y-m-d\TH:i') }}" required>
                </div>
                <div class="mb-3">
                    <label for="ends_at" class="form-label">إلى</label>
                    <input type="datetime-local" name="ends_at" id="ends_at" class="form-control" value="{{ $appointment->ends_at->format('Y-m-d\TH:i') }}" required>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">ملاحظات</label>
                    <textarea name="notes" id="notes" class="form-control">{{ $appointment->notes }}</textarea>
                </div>
                <button class="btn btn-warning w-100">حفظ التعديلات</button>
            </form>
        </div>
    </div>
</div>
@endsection
