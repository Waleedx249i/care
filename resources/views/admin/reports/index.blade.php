@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Reports & Analytics</h3>
        <div>
            <button id="exportRevenue" class="btn btn-sm btn-outline-secondary">Export Revenue CSV</button>
            <button id="exportServices" class="btn btn-sm btn-outline-secondary">Export Services CSV</button>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3"><input id="from" type="date" class="form-control form-control-sm"></div>
        <div class="col-md-3"><input id="to" type="date" class="form-control form-control-sm"></div>
        <div class="col-md-3">
            <select id="filterDoctor" class="form-select form-select-sm">
                <option value="">All doctors</option>
                @foreach($doctors as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select id="filterService" class="form-select form-select-sm">
                <option value="">All services</option>
                @foreach($services as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card p-3">
                <h6>Revenue by Month</h6>
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card p-3">
                <h6>Appointments by Specialization</h6>
                <canvas id="specialtyChart"></canvas>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="card p-3">
                <h6>Services Usage Frequency</h6>
                <canvas id="servicesChart"></canvas>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    async function fetchJson(url, params = {}){
        const ps = new URLSearchParams(params);
        const r = await fetch(url + '?' + ps.toString());
        return r.json();
    }

    async function loadReports(){
        const params = {from: document.getElementById('from').value, to: document.getElementById('to').value, doctor_id: document.getElementById('filterDoctor').value, service_id: document.getElementById('filterService').value};

        const rev = await fetchJson('/admin/reports/revenue', params);
        const labels = rev.map(r=>r.month);
        const data = rev.map(r=>parseFloat(r.revenue));
        renderBar('revenueChart', labels, data, 'Revenue');

        const spec = await fetchJson('/admin/reports/appointments-specialty', params);
        renderPie('specialtyChart', spec.map(s=>s.specialization), spec.map(s=>s.count));

        const svc = await fetchJson('/admin/reports/services-usage', params);
        renderBar('servicesChart', svc.map(s=>s.service), svc.map(s=>s.total), 'Usage');
    }

    function renderBar(id, labels, data, label){
        const ctx = document.getElementById(id).getContext('2d');
        if (window[id]) window[id].destroy();
        window[id] = new Chart(ctx, {type:'bar', data:{labels, datasets:[{label, data, backgroundColor:'#4e73df'}]}, options:{responsive:true, maintainAspectRatio:false}});
    }

    function renderPie(id, labels, data){
        const ctx = document.getElementById(id).getContext('2d');
        if (window[id]) window[id].destroy();
        window[id] = new Chart(ctx, {type:'pie', data:{labels, datasets:[{data, backgroundColor:['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b']}]}, options:{responsive:true, maintainAspectRatio:false}});
    }

    document.getElementById('from').addEventListener('change', loadReports);
    document.getElementById('to').addEventListener('change', loadReports);
    document.getElementById('filterDoctor').addEventListener('change', loadReports);
    document.getElementById('filterService').addEventListener('change', loadReports);

    document.getElementById('exportRevenue').addEventListener('click', ()=>{
        const params = new URLSearchParams({from: document.getElementById('from').value, to: document.getElementById('to').value});
        location.href = '/admin/reports/export/revenue?'+params.toString();
    });
    document.getElementById('exportServices').addEventListener('click', ()=>{
        const params = new URLSearchParams({from: document.getElementById('from').value, to: document.getElementById('to').value});
        location.href = '/admin/reports/export/services?'+params.toString();
    });

    // initial load
    loadReports();
</script>
@endsection
@endsection