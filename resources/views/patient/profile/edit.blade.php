@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Profile & Settings</h2>

    <form method="post" action="{{ route('patient.profile.update') }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Personal Info</h5>
                        <div class="mb-2">
                            <label class="form-label">Name</label>
                            <input class="form-control" value="{{ $patient->name }}" readonly>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Gender</label>
                            <input class="form-control" value="{{ $patient->gender }}" readonly>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Birth Date</label>
                            <input class="form-control" value="{{ optional($patient->birth_date)->toDateString() }}" readonly>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Phone</label>
                            <input name="phone" value="{{ old('phone', $patient->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $patient->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Account</h5>
                        <div class="mb-2">
                            <label class="form-label">Change Password</label>
                            <input type="password" name="current_password" class="form-control mb-2" placeholder="Current password">
                            <input type="password" name="new_password" class="form-control mb-2" placeholder="New password">
                            <input type="password" name="new_password_confirmation" class="form-control" placeholder="Confirm new password">
                        </div>

                        <h6 class="mt-3">Notifications</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="notify_email" id="notify_email" {{ $patient->notify_email ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify_email">Email notifications</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="notify_sms" id="notify_sms" {{ $patient->notify_sms ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify_sms">SMS notifications</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary">Save</button>
                    <a href="{{ route('patient.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
