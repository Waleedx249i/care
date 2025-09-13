@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Admin Dashboard</h2>

    <div class="row mt-3">
        <div class="col-6 col-md-3 mb-3">
            <div class="card p-3">
                <div class="text-muted">Total Doctors</div>
                <div class="h3">{{ $totalDoctors }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card p-3">
                <div class="text-muted">Total Patients</div>
                <div class="h3">{{ $totalPatients }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card p-3">
                <div class="text-muted">Today's Appointments</div>
                <div class="h3">{{ $todaysAppointments }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card p-3">
                <div class="text-muted">Monthly Revenue</div>
                <div class="h3">{{ number_format($monthlyRevenue,2) }}</div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 mb-3">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Charts</h5>
                </div>
                <div class="charts-wrapper" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                    <div style="min-width:700px; display:flex; gap:20px;">
                        <div style="flex:1; min-width:320px;">
                            <canvas id="appointmentsChart"></canvas>
                        </div>
                        <div style="flex:1; min-width:320px;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-lg-6 mb-3">
            <div class="card p-3">
                <h5>Latest Invoices</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th class="text-end">Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestInvoices as $inv)
                                <tr>
                                    <td>{{ $inv->id }}</td>
                                    <td>{{ $inv->patient->name ?? $inv->patient->first_name.' '.$inv->patient->last_name }}</td>
                                    <td>{{ $inv->doctor->user->name ?? $inv->doctor->name }}</td>
                                    <td class="text-end">{{ number_format($inv->net_total,2) }}</td>
                                    <td>{{ ucfirst($inv->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mb-3">
            <div class="card p-3">
                <h5>Latest Patients</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestPatients as $p)
                                <tr>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ $p->code }}</td>
                                    <td>{{ $p->created_at->toDateString() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dates = {!! json_encode($dates) !!};
    const counts = {!! json_encode($counts) !!};

    const appCtx = document.getElementById('appointmentsChart').getContext('2d');
    new Chart(appCtx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Appointments',
                data: counts,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.1)',
                tension: 0.2
            }]
        },
        options: {responsive:true, maintainAspectRatio:false}
    });

    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'doughnut',
        data: {
            labels: ['Paid','Unpaid'],
            datasets: [{
                data: [{{ (float)$paid }}, {{ (float)$unpaid }}],
                backgroundColor: ['#28a745','#dc3545']
            }]
        },
        options: {responsive:true, maintainAspectRatio:false}
    });
</script>
