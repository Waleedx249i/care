@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card mt-4">
                <div class="card-header bg-warning text-white">بيانات الطبيب</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">الاسم الكامل</label>
                        <p class="form-control-plaintext">{{ $doctor->user->name ?? 'غير محدد' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">البريد الإلكتروني</label>
                        <p class="form-control-plaintext">{{ $doctor->user->email ?? 'غير محدد' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">التخصص</label>
                        <p class="form-control-plaintext">{{ $doctor->specialty ?? 'غير محدد' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الهاتف</label>
                        <p class="form-control-plaintext">{{ $doctor->phone ?? 'غير محدد' }}</p>
                    </div>
                    <div class="d-grid">
                        <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-warning">تعديل</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection