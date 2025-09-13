@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Patients</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">Add Patient</button>
    </div>

    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search by name, code or phone" value="{{ request('q') }}">
            <button class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive d-none d-md-block">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Phone</th>
                            <th>Age</th>
                            <th>Last Visit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $p)
                            <tr>
                                <td>{{ $p->code }}</td>
                                <td>{{ $p->name }}</td>
                                <td>{{ ucfirst($p->gender) }}</td>
                                <td>{{ $p->phone }}</td>
                                <td>{{ $p->birth_date ? $p->birth_date->age : '—' }}</td>
                                <td>{{ optional($p->appointments()->latest('starts_at')->first())->starts_at ? optional($p->appointments()->latest('starts_at')->first())->starts_at->format('Y-m-d') : '—' }}</td>
                                <td>
                                    <a href="{{ route('staff.patients.show', $p->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('staff.patients.edit', $p->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7">No patients found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-block d-md-none p-3">
                @forelse($patients as $p)
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div><strong>{{ $p->name }}</strong></div>
                                <div class="text-muted">{{ $p->code }}</div>
                            </div>
                            <div>{{ $p->phone }} • {{ ucfirst($p->gender) }}</div>
                            <div class="mt-2">
                                <a href="{{ route('staff.patients.show', $p->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('staff.patients.edit', $p->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">No patients found.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-3">{{ $patients->withQueryString()->links() }}</div>

    {{-- Add Patient Modal --}}
    <div class="modal fade" id="addPatientModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('staff.patients.store') }}">
                    @csrf
                    <div class="modal-header"><h5 class="modal-title">Add Patient</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="mb-2"><label class="form-label">Code</label><input name="code" class="form-control" required></div>
                        <div class="mb-2"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
                        <div class="mb-2"><label class="form-label">Gender</label><select name="gender" class="form-select"><option value="">Select</option><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select></div>
                        <div class="mb-2"><label class="form-label">Phone</label><input name="phone" class="form-control"></div>
                        <div class="mb-2"><label class="form-label">Birth Date</label><input type="date" name="birth_date" class="form-control"></div>
                        <div class="mb-2"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2"></textarea></div>
                        <div class="mb-2"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button class="btn btn-primary">Add</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
