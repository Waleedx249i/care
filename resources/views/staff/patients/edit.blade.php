@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Edit Patient #{{ $patient->code }}</h2>
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('staff.patients.update', $patient->id) }}">@csrf @method('PUT')
        <div class="row g-2">
            <div class="col-md-4"><label class="form-label">Code</label><input name="code" class="form-control" value="{{ $patient->code }}" required></div>
            <div class="col-md-8"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ $patient->name }}" required></div>
            <div class="col-md-4"><label class="form-label">Gender</label><select name="gender" class="form-select"><option value="">Select</option><option value="male" {{ $patient->gender=='male'?'selected':'' }}>Male</option><option value="female" {{ $patient->gender=='female'?'selected':'' }}>Female</option><option value="other" {{ $patient->gender=='other'?'selected':'' }}>Other</option></select></div>
            <div class="col-md-4"><label class="form-label">Phone</label><input name="phone" class="form-control" value="{{ $patient->phone }}"></div>
            <div class="col-md-4"><label class="form-label">Birth Date</label><input type="date" name="birth_date" class="form-control" value="{{ $patient->birth_date?->format('Y-m-d') }}"></div>
            <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control">{{ $patient->address }}</textarea></div>
            <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control">{{ $patient->notes }}</textarea></div>
        </div>
        <div class="mt-3"><button class="btn btn-primary">Save</button><a href="{{ route('staff.patients.index') }}" class="btn btn-secondary">Back</a></div>
    </form>
</div>
@endsection
