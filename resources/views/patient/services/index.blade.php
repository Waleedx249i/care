@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Services</h2>
        <form class="row g-2" method="get" style="max-width:540px;">
            <div class="col-12 col-sm-6">
                <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search services...">
            </div>
            <div class="col-5 col-sm-3">
                <input name="min_price" value="{{ request('min_price') }}" type="number" step="0.01" class="form-control" placeholder="Min">
            </div>
            <div class="col-5 col-sm-3">
                <input name="max_price" value="{{ request('max_price') }}" type="number" step="0.01" class="form-control" placeholder="Max">
            </div>
            <div class="col-2 d-none">
                <button class="btn btn-primary">Go</button>
            </div>
        </form>
    </div>

    <div class="row">
        @forelse($services as $s)
            <div class="col-12 col-md-6 col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title">{{ $s->name }}</h5>
                                @if($s->description)
                                    <p class="small text-muted">{{ Str::limit($s->description, 120) }}</p>
                                @endif
                            </div>
                            <div class="text-end">
                                <div class="h5">{{ number_format($s->price,2) }}</div>
                                @if(!$s->active)
                                    <div class="small text-danger">Inactive</div>
                                @else
                                    <div class="small text-success">Active</div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('appointments.book', ['service_id' => $s->id]) }}" class="btn btn-sm btn-primary">Book Appointment with this Service</a>
                            <a href="#" class="btn btn-sm btn-outline-secondary">Details</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No services found.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">{{ $services->links() }}</div>
</div>
@endsection
