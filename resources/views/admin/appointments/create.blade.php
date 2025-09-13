@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mt-4">
        <div class="card-header bg-success text-white">إضافة موعد</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.appointments.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="patient_id" class="form-label">المريض</label>
                    <select name="patient_id" id="patient_id" class="form-control" required>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="doctor_id" class="form-label">الطبيب</label>
                    <select name="doctor_id" id="doctor_id" class="form-control" required>
                        @foreach($doctors as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="starts_at" class="form-label">من</label>
                    <input type="datetime-local" name="starts_at" id="starts_at" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="ends_at" class="form-label">إلى</label>
                    <input type="datetime-local" name="ends_at" id="ends_at" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">ملاحظات</label>
                    <textarea name="notes" id="notes" class="form-control"></textarea>
                </div>
                <button class="btn btn-success w-100">حفظ</button>
            </form>
        </div>
    </div>
</div>
@endsection
