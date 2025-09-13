@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card mt-4">
                <div class="card-header bg-warning text-white">تعديل بيانات الطبيب</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.doctors.update', $doctor) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="specialty" class="form-label">التخصص</label>
                            <input type="text" name="specialty" id="specialty" class="form-control" value="{{ $doctor->specialty }}">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">الهاتف</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ $doctor->phone }}">
                        </div>
                        <button type="submit" class="btn btn-warning w-100">تعديل</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
