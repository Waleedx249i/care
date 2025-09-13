@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Doctors Directory</h3>
        <form class="d-flex" method="GET" action="{{ route('admin.doctors.index') }}">
            <input name="specialty" class="form-control form-control-sm me-2" placeholder="Filter by specialty" value="{{ request('specialty') }}">
            <button class="btn btn-sm btn-outline-primary">Filter</button>
        </form>
    </div>

    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Phone</th>
                    <th class="text-center">Patients</th>
                    <th class="text-center">Working Days</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($doctors as $doctor)
                <tr>
                    <td>
                        <a href="{{ route('admin.doctors.show', optional($doctor)->id) }}">{{ optional($doctor)->name ?? '-' }}</a>
                    </td>
                    <td>{{ optional($doctor)->specialty ?? '-' }}</td>
                    <td>{{ optional($doctor)->phone ?? '-' }}</td>
                    <td class="text-center">{{ data_get($patientsCounts, optional($doctor)->id, 0) }}</td>
                    <td class="text-center">{{ data_get($wh, optional($doctor)->id . '.days', 0) }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.doctors.show', optional($doctor)->id) }}" class="btn btn-sm btn-outline-secondary">View Profile</a>
                        <a href="{{ route('admin.doctors.working-hours.edit', optional($doctor)->id) }}" class="btn btn-sm btn-primary ms-1">Edit Working Hours</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="d-md-none">
        <div class="row g-3">
            @foreach($doctors as $doctor)
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-1"><a href="{{ route('admin.doctors.show', optional($doctor)->id) }}">{{ optional($doctor)->name ?? '-' }}</a></h5>
                                <div class="text-muted small">{{ optional($doctor)->specialty ?? '—' }}</div>
                                <div class="mt-2 small"><strong>Phone:</strong> {{ optional($doctor)->phone ?? '—' }}</div>
                            </div>
                            <div class="text-end">
                                <div class="small">Patients: <strong>{{ data_get($patientsCounts, optional($doctor)->id, 0) }}</strong></div>
                                <div class="small">Days: <strong>{{ data_get($wh, optional($doctor)->id . '.days', 0) }}</strong></div>
                                <div class="mt-2">
                                    <a href="{{ route('admin.doctors.show', optional($doctor)->id) }}" class="btn btn-sm btn-outline-secondary">Profile</a>
                                    <a href="{{ route('admin.doctors.working-hours.edit', optional($doctor)->id) }}" class="btn btn-sm btn-primary ms-1">Hours</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="mt-3">
        {{ $doctors->links() }}
    </div>
</div>

@endsection