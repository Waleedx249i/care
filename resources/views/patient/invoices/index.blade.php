@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>My Invoices</h2>
        <form class="d-flex" method="get">
            <select name="status" class="form-select me-2" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="unpaid" {{ request('status')=='unpaid'?'selected':'' }}>Unpaid</option>
                <option value="paid" {{ request('status')=='paid'?'selected':'' }}>Paid</option>
                <option value="overdue" {{ request('status')=='overdue'?'selected':'' }}>Overdue</option>
            </select>
        </form>
    </div>

    <div class="row">
        @foreach($invoices as $inv)
            <div class="col-12 col-md-6 col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title">Invoice #{{ $inv->id }}</h5>
                                <div class="text-muted">Doctor: {{ $inv->doctor->user->name ?? $inv->doctor->name }}</div>
                            </div>
                            <div class="text-end">
                                <div class="h5">{{ number_format($inv->net_total,2) }}</div>
                                <div class="small">Due: {{ optional($inv->due_date)->toDateString() }}</div>
                            </div>
                        </div>

                        <div class="mt-3 mb-2">
                            <span class="badge bg-{{ $inv->status=='paid'?'success':($inv->status=='partial'?'warning':'danger') }}">{{ ucfirst($inv->status) }}</span>
                        </div>

                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('patient.invoices.show', $inv) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            @if($inv->status != 'paid')
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal" data-invoice-id="{{ $inv->id }}" data-invoice-net="{{ $inv->net_total }}" data-invoice-paid="{{ $inv->payments_sum_amount ?? 0 }}">Pay</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-3">{{ $invoices->links() }}</div>

    @include('patient.invoices._payment_modal')
</div>
@endsection
