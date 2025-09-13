@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Appointments Management</h3>
        <div>
            <button id="toggleView" class="btn btn-sm btn-outline-secondary">Toggle List</button>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <select id="filterDoctor" class="form-select form-select-sm">
                <option value="">All doctors</option>
                @foreach($doctors as $d)
                <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->specialty }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select id="filterPatient" class="form-select form-select-sm">
                <option value="">All patients</option>
                @foreach($patients as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select id="filterStatus" class="form-select form-select-sm">
                <option value="">Any status</option>
                <option value="scheduled">Scheduled</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <div class="col-md-4 d-flex">
            <input id="fromDate" type="date" class="form-control form-control-sm me-2">
            <input id="toDate" type="date" class="form-control form-control-sm me-2">
            <button id="applyFilters" class="btn btn-sm btn-primary">Apply</button>
        </div>
    </div>

    <div id="calendarWrap">
        <div id="calendar"></div>
    </div>

    <div id="listView" style="display:none; margin-top:1rem;">
        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Starts At</th>
                    <th>Ends At</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="appointmentsList"></tbody>
        </table>
    </div>

    <div id="mobileDayList" class="d-md-none mt-3"></div>
</div>

@section('scripts')
<script>
    let listVisible = false;
    document.getElementById('toggleView').addEventListener('click', ()=>{
        listVisible = !listVisible;
        document.getElementById('listView').style.display = listVisible ? 'block' : 'none';
        document.getElementById('calendarWrap').style.display = listVisible ? 'none' : 'block';
    });

    async function loadAppointments() {
        const params = new URLSearchParams();
        if (document.getElementById('filterDoctor').value) params.set('doctor_id', document.getElementById('filterDoctor').value);
        if (document.getElementById('filterPatient').value) params.set('patient_id', document.getElementById('filterPatient').value);
        if (document.getElementById('filterStatus').value) params.set('status', document.getElementById('filterStatus').value);
        if (document.getElementById('fromDate').value) params.set('from', document.getElementById('fromDate').value);
        if (document.getElementById('toDate').value) params.set('to', document.getElementById('toDate').value);

        const res = await fetch('/admin/appointments/api?'+params.toString());
        const items = await res.json();

        // populate list
        const tbody = document.getElementById('appointmentsList');
        tbody.innerHTML = '';
        items.forEach(a => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${a.patient ? a.patient.name : '—'}</td>
                <td>${a.doctor ? a.doctor.name : '—'}</td>
                <td>${a.starts_at}</td>
                <td>${a.ends_at}</td>
                <td>${a.status}</td>
                <td>${a.notes || ''}</td>
                <td><a class="btn btn-sm btn-outline-secondary" href="/admin/appointments/${a.id}/edit">Edit</a>
                <button class="btn btn-sm btn-danger ms-1" onclick="cancel(${a.id})">Cancel</button>
                <button class="btn btn-sm btn-warning ms-1" onclick="showReassign(${a.id})">Reassign</button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        // mobile day list
        const mobile = document.getElementById('mobileDayList');
        mobile.innerHTML = '';
        const byDay = {};
        items.forEach(a => {
            const d = new Date(a.starts_at).toLocaleDateString();
            byDay[d] = byDay[d]||[]; byDay[d].push(a);
        });
        for (const day of Object.keys(byDay)){
            const card = document.createElement('div');
            card.className = 'card mb-2';
            const body = document.createElement('div'); body.className='card-body';
            body.innerHTML = `<h6>${day}</h6>`;
            byDay[day].forEach(a=>{
                const row = document.createElement('div');
                row.className='d-flex justify-content-between py-1 border-top';
                row.innerHTML = `<div><strong>${a.starts_at}</strong> - ${a.patient ? a.patient.name : '—'}</div><div><button class="btn btn-sm btn-outline-secondary" onclick="location.href='/admin/appointments/${a.id}/edit'">Edit</button></div>`;
                body.appendChild(row);
            });
            card.appendChild(body); mobile.appendChild(card);
        }
    }

    document.getElementById('applyFilters').addEventListener('click', ()=>loadAppointments());
    document.getElementById('filterDoctor').addEventListener('change', ()=>loadAppointments());
    document.getElementById('filterPatient').addEventListener('change', ()=>loadAppointments());
    document.getElementById('filterStatus').addEventListener('change', ()=>loadAppointments());

    async function cancel(id){
        await fetch('/admin/appointments/'+id+'/cancel', {method:'POST', headers:{'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')}});
        loadAppointments();
    }

    function showReassign(id){
        const doctorId = prompt('Enter new doctor id to reassign:');
        if (!doctorId) return;
        fetch('/admin/appointments/'+id+'/reassign', {method:'POST', headers:{'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),'Content-Type':'application/json'}, body: JSON.stringify({doctor_id:doctorId})})
            .then(r=>r.json()).then(()=>loadAppointments()).catch(e=>alert('Error'));
    }

    // initial load
    loadAppointments();
</script>
@endsection
