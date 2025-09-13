@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>My Medical Records</h3>
    </div>

    <div class="table-responsive d-none d-md-block">
        <table class="table table-striped">
            <thead><tr><th>Date</th><th>Doctor</th><th>Diagnosis</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($records as $r)
                    <tr>
                        <td>{{ optional($r->visit_date)->toDateString() }}</td>
                        <td>{{ $r->doctor->name }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($r->diagnosis, 80) }}</td>
                        <td><a class="btn btn-sm btn-outline-secondary" href="{{ route('patient.medical_records.show', $r) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-block d-md-none">
        <div class="row">
            @foreach($records as $r)
                <div class="col-12 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="fw-bold">{{ optional($r->visit_date)->toDayDateString() }}</div>
                                    <div class="text-muted">Dr. {{ $r->doctor->name }}</div>
                                </div>
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('patient.medical_records.show', $r) }}">View</a>
                            </div>
                            <div class="mt-2 collapse" id="diag-{{ $r->id }}">{{ $r->diagnosis }}</div>
                            <button class="btn btn-sm btn-link p-0" data-bs-toggle="collapse" data-bs-target="#diag-{{ $r->id }}">Toggle diagnosis</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{ $records->links() }}
</div>

@endsection
