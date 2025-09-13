@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-3">
        <div class="col-12">
            <h2>Staff Dashboard</h2>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    {{-- KPI cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-center p-3">
                <div class="h5">Today's Appointments</div>
                <div class="display-6">{{ $kpiAppointments }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center p-3">
                <div class="h5">New Patients Today</div>
                <div class="display-6">{{ $newPatientsToday }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center p-3">
                <div class="h5">Pending Invoices</div>
                <div class="display-6">{{ $kpiPendingInvoices }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center p-3">
                <div class="h5">Collected Today</div>
                <div class="display-6">{{ number_format($kpiCollected, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">Today's Appointments</div>
                <div class="card-body p-0">
                    {{-- Desktop table --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointmentsToday as $appt)
                                    <tr>
                                        <td>{{ $appt->starts_at->format('H:i') }}</td>
                                        <td>{{ optional($appt->patient)->name ?? '—' }}</td>
                                        <td>{{ optional($appt->doctor)->name ?? '—' }}</td>
                                        <td>{{ ucfirst($appt->status) }}</td>
                                        <td>
                                            <form action="{{ route('staff.appointments.checkin', $appt->id) }}" method="POST" style="display:inline">@csrf<button class="btn btn-sm btn-success">Check-in</button></form>
                                            <form action="{{ route('staff.appointments.cancel', $appt->id) }}" method="POST" style="display:inline;margin-left:6px">@csrf<button class="btn btn-sm btn-danger">Cancel</button></form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5">No appointments today.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile cards --}}
                    <div class="d-block d-md-none p-3">
                        @forelse($appointmentsToday as $appt)
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div><strong>{{ $appt->starts_at->format('H:i') }}</strong> — {{ optional($appt->patient)->name ?? '—' }}</div>
                                        <div><span class="badge bg-secondary">{{ ucfirst($appt->status) }}</span></div>
                                    </div>
                                    <div class="text-muted">Doctor: {{ optional($appt->doctor)->name ?? '—' }}</div>
                                    <div class="mt-2">
                                        <form action="{{ route('staff.appointments.checkin', $appt->id) }}" method="POST" style="display:inline">@csrf<button class="btn btn-sm btn-success">Check-in</button></form>
                                        <form action="{{ route('staff.appointments.cancel', $appt->id) }}" method="POST" style="display:inline;margin-left:6px">@csrf<button class="btn btn-sm btn-danger">Cancel</button></form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-muted">No appointments today.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">Pending Invoices</div>
                <div class="card-body p-0">
                    <div class="table-responsive d-none d-md-block">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Amount</th>
                                    <th>Due</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingInvoices as $inv)
                                    <tr>
                                        <td>{{ $inv->id }}</td>
                                        <td>{{ optional($inv->patient)->name ?? '—' }}</td>
                                        <td>{{ optional($inv->doctor)->name ?? '—' }}</td>
                                        <td>{{ number_format($inv->net_total, 2) }}</td>
                                        <td>{{ optional($inv->due_date)->format('Y-m-d') ?? '—' }}</td>
                                        <td>
                                            <a href="{{ route('admin.invoices.show', $inv->id) }}" class="btn btn-sm btn-primary">Collect Payment</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6">No pending invoices.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-block d-md-none p-3">
                        @forelse($pendingInvoices as $inv)
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div><strong>#{{ $inv->id }}</strong></div>
                                        <div>{{ number_format($inv->net_total,2) }}</div>
                                    </div>
                                    <div class="text-muted">Patient: {{ optional($inv->patient)->name ?? '—' }}</div>
                                    <div class="text-muted">Due: {{ optional($inv->due_date)->format('Y-m-d') ?? '—' }}</div>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.invoices.show', $inv->id) }}" class="btn btn-sm btn-primary">Collect</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-muted p-3">No pending invoices.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
