@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daily Report</h2>
        <form method="GET" class="d-flex" action="{{ route('staff.reports.daily') }}">
            <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="form-control me-2">
            <button class="btn btn-outline-primary">Go</button>
        </form>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Appointments</h5>
                    <div>Total today: <strong>{{ $total }}</strong></div>
                    <div>Completed: <strong>{{ $completed }}</strong></div>
                    <div>Canceled: <strong>{{ $canceled }}</strong></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Payments</h5>
                    <div>Total collected: <strong>{{ number_format($totalPayments,2) }}</strong></div>
                    <div class="mt-2">
                        @foreach($byMethod as $m => $amt)
                            <div>{{ ucfirst($m) }}: <strong>{{ number_format($amt,2) }}</strong></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-end">
                    <a href="{{ route('staff.reports.export', ['date' => $date->format('Y-m-d')]) }}" class="btn btn-primary">Export CSV</a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h5>Payments (detailed)</h5>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>ID</th><th>Invoice</th><th>Amount</th><th>Method</th><th>Reference</th><th>Paid at</th></tr></thead>
                        <tbody>
                            @php
                                $start = $date->copy()->startOfDay();
                                $end = $date->copy()->endOfDay();
                                $payments = \App\Models\Payment::whereBetween('paid_at', [$start, $end])->orderBy('paid_at','desc')->get();
                            @endphp
                            @forelse($payments as $p)
                                <tr>
                                    <td>{{ $p->id }}</td>
                                    <td>{{ $p->invoice_id }}</td>
                                    <td>{{ number_format($p->amount,2) }}</td>
                                    <td>{{ ucfirst($p->method) }}</td>
                                    <td>{{ $p->reference }}</td>
                                    <td>{{ optional($p->paid_at)->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6">No payments for this date.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
