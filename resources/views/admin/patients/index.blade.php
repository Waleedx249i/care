@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Patients Directory</h3>
        <a href="{{ route('admin.patients.create') ?? '#' }}" class="btn btn-sm btn-success">Add Patient</a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-sm-2">
            <select name="gender" class="form-select form-select-sm">
                <option value="">Any gender</option>
                <option value="male" {{ request('gender')=='male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ request('gender')=='female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>
        <div class="col-sm-3">
            <div class="input-group input-group-sm">
                <input name="min_age" class="form-control" placeholder="Min age" value="{{ request('min_age') }}">
                <input name="max_age" class="form-control" placeholder="Max age" value="{{ request('max_age') }}">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="input-group input-group-sm">
                <input name="registered_from" type="date" class="form-control" value="{{ request('registered_from') }}">
                <input name="registered_to" type="date" class="form-control" value="{{ request('registered_to') }}">
            </div>
        </div>
        <div class="col-sm-1">
            <button class="btn btn-sm btn-outline-primary">Filter</button>
        </div>
    </form>

    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Age</th>
                    <th>Last Visit</th>
                    <th>Registered On</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $p)
                <tr>
                    <td>{{ $p->code ?? $p->id }}</td>
                    <td><a href="{{ route('admin.patients.show', $p->id) ?? '#' }}">{{ $p->name }}</a></td>
                    <td>{{ ucfirst($p->gender ?? '-') }}</td>
                    <td>{{ $p->phone ?? '-' }}</td>
                    <td>{{ $p->birth_date ? \Carbon\Carbon::parse($p->birth_date)->age : '-' }}</td>
                    <td>{{ optional($visits[$p->id] ?? null)->last_visit ? \Carbon\Carbon::parse($visits[$p->id]->last_visit)->toDateString() : '-' }}</td>
                    <td>{{ $p->created_at ? $p->created_at->toDateString() : '-' }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.patients.show', $p->id) ?? '#' }}" class="btn btn-sm btn-outline-secondary">View</a>
                        <form method="POST" action="{{ route('admin.patients.deactivate', $p->id) }}" style="display:inline-block;">
                            @csrf
                            <button class="btn btn-sm btn-danger ms-1">Deactivate</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-md-none">
        <div class="row g-3">
            @foreach($patients as $p)
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-1"><a href="{{ route('admin.patients.show', $p->id) ?? '#' }}">{{ $p->name }}</a></h5>
                                <div class="small text-muted">Code: {{ $p->code ?? $p->id }} · {{ ucfirst($p->gender ?? '-') }}</div>
                                <div class="mt-2 small">Phone: {{ $p->phone ?? '-' }}</div>
                            </div>
                            <div class="text-end">
                                <div class="small">Age: <strong>{{ $p->birth_date ? \Carbon\Carbon::parse($p->birth_date)->age : '-' }}</strong></div>
                                <div class="small">Last visit: <strong>{{ optional($visits[$p->id] ?? null)->last_visit ? \Carbon\Carbon::parse($visits[$p->id]->last_visit)->toDateString() : '-' }}</strong></div>
                                <div class="mt-2">
                                    <a href="{{ route('admin.patients.show', $p->id) ?? '#' }}" class="btn btn-sm btn-outline-secondary">View</a>
                                    <form method="POST" action="{{ route('admin.patients.deactivate', $p->id) }}" style="display:inline-block;">
                                        @csrf
                                        <button class="btn btn-sm btn-danger ms-1">Deactivate</button>
                                    </form>
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
        {{ $patients->links() }}
    </div>
</div>

@endsection

