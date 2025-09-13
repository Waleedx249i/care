@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Welcome, {{ $patient->name }}</h2>
    </div>

    <div class="row mb-3">
        <div class="col-md-4 col-12 mb-2">
            <div class="card h-100">
                <div class="card-body">
                    <h6>Next Appointment</h6>
                    @if($next)
                        <div>{{ $next->starts_at->toDayDateTimeString() }}</div>
                        <div class="text-muted">with Dr. {{ $next->doctor->name }}</div>
                    @else
                        <div class="text-muted">No upcoming appointments</div>
                        <a href="/doctor/calendar" class="btn btn-sm btn-primary mt-2">Book Appointment</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12 mb-2">
            <div class="card h-100">
                <div class="card-body">
                    <h6>Outstanding Balance</h6>
                    <div class="fs-4">{{ number_format($outstanding,2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12 mb-2">
            <div class="card h-100">
                <div class="card-body">
                    <h6>Last Prescription</h6>
                    @if($lastPrescription)
                        <div>{{ $lastPrescription->created_at->toDateString() }}</div>
                        <div class="text-muted">{{ implode(', ', $lastPrescription->medicines ?? []) }}</div>
                    @else
                        <div class="text-muted">No prescriptions yet</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-12 mb-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>Upcoming Appointments</div>
                    <a href="/patient/appointments" class="btn btn-sm btn-link">View all</a>
                </div>
                <div class="card-body">
                    @if($upcoming->isEmpty())
                        <div class="text-center p-4">
                            <p class="text-muted">You have no upcoming appointments.</p>
                            <a href="/doctor/services" class="btn btn-primary">View Services</a>
                        </div>
                    @else
                        <ul class="list-group">
                            @foreach($upcoming as $a)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div>{{ $a->starts_at->toDayDateTimeString() }}</div>
                                        <div class="text-muted">Dr. {{ $a->doctor->name }}</div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="/patient/appointments/{{ $a->id }}" class="btn btn-sm btn-outline-secondary">View</a>
                                        <a href="/patient/appointments/{{ $a->id }}/reschedule" class="btn btn-sm btn-outline-primary">Reschedule</a>
                                        <form method="post" action="/patient/appointments/{{ $a->id }}/cancel" onsubmit="return confirm('Cancel appointment?')">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger">Cancel</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-12 mb-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>Recent Medical Records</div>
                    <a href="/patient/medical-records" class="btn btn-sm btn-link">View all</a>
                </div>
                <div class="card-body">
                    @if($records->isEmpty())
                        <div class="text-center p-4">
                            <p class="text-muted">No medical records yet.</p>
                            <a href="/doctor/services" class="btn btn-primary">View Services</a>
                        </div>
                    @else
                        <ul class="list-group">
                            @foreach($records as $r)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div>{{ optional($r->visit_date)->toDateString() }}</div>
                                        <div class="text-muted">{{ \Illuminate\Support\Str::limit($r->diagnosis, 80) }}</div>
                                    </div>
                                    <a href="/patient/medical-records/{{ $r->id }}" class="btn btn-sm btn-outline-secondary">View</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
