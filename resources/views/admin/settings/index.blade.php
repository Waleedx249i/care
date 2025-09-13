@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h2>System Settings</h2>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">General</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Clinic Name</label>
                            <input type="text" name="clinic_name" class="form-control" value="{{ old('clinic_name', $clinic_name) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Logo</label>
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    @if($clinic_logo)
                                        <img src="{{ $clinic_logo }}" alt="logo" style="max-width:120px;max-height:80px;object-fit:contain;border:1px solid #eee;padding:4px;background:#fff">
                                    @else
                                        <div style="width:120px;height:80px;border:1px dashed #ddd;display:flex;align-items:center;justify-content:center;color:#aaa">No logo</div>
                                    @endif
                                </div>
                                <div style="flex:1">
                                    <input type="file" name="clinic_logo" class="form-control">
                                    <small class="text-muted">Max 2MB. PNG/JPG/GIF</small>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="clinic_address" class="form-control" rows="3">{{ old('clinic_address', $clinic_address) }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="clinic_phone" class="form-control" value="{{ old('clinic_phone', $clinic_phone) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="clinic_email" class="form-control" value="{{ old('clinic_email', $clinic_email) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">Notifications</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Email Provider (driver name)</label>
                            <input type="text" name="notification_email_provider" class="form-control" value="{{ old('notification_email_provider', $notification_email_provider) }}">
                            <small class="text-muted">E.g., smtp, mailgun, ses</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">SMS Provider</label>
                            <input type="text" name="notification_sms_provider" class="form-control" value="{{ old('notification_sms_provider', $notification_sms_provider) }}">
                            <small class="text-muted">E.g., twilio, nexmo</small>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Billing</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tax Rate (%)</label>
                            <input type="number" step="0.01" name="billing_tax_rate" class="form-control" value="{{ old('billing_tax_rate', $billing_tax_rate) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Default Currency</label>
                            <input type="text" name="billing_currency" class="form-control" value="{{ old('billing_currency', $billing_currency) }}">
                            <small class="text-muted">ISO code, e.g., USD</small>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Roles & Permissions (JSON)</div>
                    <div class="card-body">
                        <textarea name="roles_permissions" class="form-control" rows="6">{{ old('roles_permissions', $roles_permissions) }}</textarea>
                        <small class="text-muted">Optional JSON structure mapping modules to roles; used for quick toggles. For more advanced control use the Permissions page.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Save</button>
                <a href="{{ route('admin.settings.reset') }}" class="btn btn-outline-secondary" onclick="event.preventDefault(); if(confirm('Reset to defaults?')){ document.getElementById('reset-form').submit(); }">Reset Defaults</a>
            </div>
        </div>
    </form>

    <form id="reset-form" action="{{ route('admin.settings.reset') }}" method="POST" style="display:none">@csrf</form>
</div>
@endsection
