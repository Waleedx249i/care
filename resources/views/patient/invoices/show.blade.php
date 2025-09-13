@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Invoice #{{ $invoice->id }}</h2>
        <div>
            @if($invoice->status != 'paid')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal" data-invoice-id="{{ $invoice->id }}" data-invoice-net="{{ $invoice->net_total }}" data-invoice-paid="{{ $invoice->payments->sum('amount') }}">Pay Now</button>
            @endif
            <a href="{{ route('patient.invoices.index') }}" class="btn btn-link">Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-2">Patient</h5>
                    <div>{{ $invoice->patient->name ?? ($invoice->patient->first_name.' '.$invoice->patient->last_name) }}</div>
                    <div class="text-muted">Phone: {{ $invoice->patient->phone }}</div>
                    <hr>
                    <h5 class="mb-2">Doctor</h5>
                    <div>{{ $invoice->doctor->user->name ?? $invoice->doctor->name }}</div>
                    <div class="text-muted">{{ $invoice->doctor->specialty ?? '' }}</div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5>Items</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Line Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $it)
                                    <tr>
                                        <td>{{ $it->service->name ?? 'Service #' . $it->service_id }}</td>
                                        <td class="text-end">{{ $it->qty }}</td>
                                        <td class="text-end">{{ number_format($it->unit_price,2) }}</td>
                                        <td class="text-end">{{ number_format($it->line_total,2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Summary</h5>
                    <div class="d-flex justify-content-between">Subtotal <div>{{ number_format($invoice->total,2) }}</div></div>
                    <div class="d-flex justify-content-between mt-2">Discount <div>{{ number_format($invoice->discount ?? 0,2) }}</div></div>
                    <div class="d-flex justify-content-between mt-2">Net <div>{{ number_format($invoice->net_total,2) }}</div></div>
                    <div class="d-flex justify-content-between mt-2">Payments <div>{{ number_format($paid,2) }}</div></div>
                    <div class="d-flex justify-content-between mt-2">Remaining <div>{{ number_format($remaining,2) }}</div></div>
                    <div class="mt-3 small text-muted">Status: {{ ucfirst($invoice->status) }}</div>
                    <div class="mt-2 small text-muted">Due: {{ optional($invoice->due_date)->toDateString() }}</div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5>Payments</h5>
                    @if($invoice->payments->isEmpty())
                        <div class="text-muted">No payments recorded.</div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($invoice->payments as $p)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <div>{{ ucfirst($p->method) }} {{ $p->reference ? ' - ' . $p->reference : '' }}</div>
                                        <div class="small text-muted">{{ optional($p->paid_at)->toDateTimeString() }}</div>
                                    </div>
                                    <div class="fw-bold">{{ number_format($p->amount,2) }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('patient.invoices._payment_modal')
</div>
@endsection
