@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Services</h2>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-6"><input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search services by name or description"></div>
        <div class="col-md-2">
            <select name="active" class="form-select">
                <option value="">All</option>
                <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active only</option>
            </select>
        </div>
        <div class="col-md-4 text-end"><button class="btn btn-primary">Filter</button></div>
    </form>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive d-none d-md-block">
                <table class="table mb-0">
                    <thead><tr><th>Name</th><th>Description</th><th style="width:120px">Price</th><th style="width:120px">Status</th></tr></thead>
                    <tbody>
                        @forelse($services as $s)
                            <tr>
                                <td>{{ $s->name }}</td>
                                <td>{{ Str::limit($s->description, 120) }}</td>
                                <td>{{ number_format($s->price,2) }}</td>
                                <td>
                                    @if($s->active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4">No services found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-block d-md-none p-3">
                <div class="row g-2">
                    @foreach($services as $s)
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between"><div><strong>{{ $s->name }}</strong><div class="text-muted small">{{ Str::limit($s->description, 100) }}</div></div><div class="text-end">{{ number_format($s->price,2) }}<div>@if($s->active)<span class="badge bg-success">Active</span>@else<span class="badge bg-secondary">Inactive</span>@endif</div></div></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">{{ $services->withQueryString()->links() }}</div>
</div>
@endsection
