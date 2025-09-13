@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card mt-4">
                <div class="card-header bg-success text-white">إضافة طبيب جديد</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.doctors.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">اختر المستخدم</label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">-- اختر --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="specialty" class="form-label">التخصص</label>
                            <input type="text" name="specialty" id="specialty" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">الهاتف</label>
                            <input type="text" name="phone" id="phone" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success w-100">إضافة</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
