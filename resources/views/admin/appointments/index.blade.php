@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">قائمة المواعيد</div>
        <div class="card-body">
            <a href="{{ route('admin.appointments.create') }}" class="btn btn-success mb-3">إضافة موعد</a>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>المريض</th>
                        <th>الطبيب</th>
                        <th>من</th>
                        <th>إلى</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->patient->name }}</td>
                            <td>{{ $appointment->doctor->name ?? $appointment->doctor->user->name }}</td>
                            <td>{{ $appointment->starts_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $appointment->ends_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $appointment->status }}</td>
                            <td>
                                <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn btn-sm btn-warning">تعديل</a>
                                <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
