@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Book Appointment</h3>

    <div id="wizard">
        <div class="mb-3">
            <label>Select Specialization</label>
            <select id="specialty" class="form-select">
                <option value="">-- select --</option>
                @foreach($specialties as $s)
                    <option value="{{ $s }}">{{ $s }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 d-none" id="step-doctors">
            <label>Select Doctor</label>
            <div id="doctors-list" class="list-group"></div>
        </div>

        <div class="mb-3 d-none" id="step-slots">
            <label>Doctor</label>
            <div id="doctor-profile" class="mb-2"></div>
            <label>Select Date</label>
            <input type="date" id="slot-date" class="form-control mb-2">
            <div id="slots-container"></div>
        </div>

        <div class="mb-3 d-none" id="step-confirm">
            <h5>Confirm Appointment</h5>
            <div id="summary"></div>
            <form id="confirmForm" method="post" action="{{ route('appointments.store') }}">
                @csrf
                <input type="hidden" name="doctor_id" id="form_doctor_id">
                <input type="hidden" name="starts_at" id="form_starts_at">
                <input type="hidden" name="ends_at" id="form_ends_at">
                <div class="mb-3">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control"></textarea>
                </div>
                <button class="btn btn-primary">Book</button>
            </form>
        </div>
    </div>
    
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    let selectedDoctor = null;
    let selectedSlot = null;

    document.getElementById('specialty').addEventListener('change', function(){
        const spec = this.value;
        if (!spec) return;
        fetch('/appointments/api/doctors?specialty='+encodeURIComponent(spec))
            .then(r=>r.json()).then(list=>{
                const container = document.getElementById('doctors-list');
                container.innerHTML = '';
                list.forEach(d=>{
                    const el = document.createElement('button');
                    el.className = 'list-group-item list-group-item-action';
                    el.innerHTML = `<div class="d-flex justify-content-between"><div>${d.name}<div class="text-muted small">${d.specialty}</div></div><div><button class="btn btn-sm btn-outline-primary">Select</button></div></div>`;
                    el.addEventListener('click', function(){
                        selectedDoctor = d;
                        document.getElementById('step-doctors').classList.add('d-none');
                        showDoctorStep(d);
                    });
                    container.appendChild(el);
                });
                document.getElementById('step-doctors').classList.remove('d-none');
            });
    });

    function showDoctorStep(d){
        document.getElementById('doctor-profile').innerHTML = `<h5>${d.name}</h5><div class="text-muted">${d.bio}</div>`;
        document.getElementById('step-slots').classList.remove('d-none');
    }

    document.getElementById('slot-date').addEventListener('change', function(){
        const date = this.value;
        if (!selectedDoctor) return alert('Select a doctor first');
        fetch('/appointments/api/slots?doctor_id='+selectedDoctor.id+'&date='+date)
            .then(r=>r.json()).then(slots=>{
                const c = document.getElementById('slots-container');
                c.innerHTML = '';
                if (slots.length===0) {
                    c.innerHTML = '<div class="text-muted">No available slots on this date.</div>';
                    return;
                }
                slots.forEach(s=>{
                    const b = document.createElement('button');
                    b.className = 'btn btn-outline-secondary m-1';
                    b.innerText = new Date(s.start).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) + ' - ' + new Date(s.end).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    b.addEventListener('click', function(){
                        selectedSlot = s;
                        showConfirm();
                    });
                    c.appendChild(b);
                });
            });
    });

    function showConfirm(){
        document.getElementById('step-slots').classList.add('d-none');
        document.getElementById('step-confirm').classList.remove('d-none');
        document.getElementById('summary').innerHTML = `<p>Doctor: ${selectedDoctor.name}</p><p>Date: ${new Date(selectedSlot.start).toLocaleDateString()}</p><p>Time: ${new Date(selectedSlot.start).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})} - ${new Date(selectedSlot.end).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</p>`;
        document.getElementById('form_doctor_id').value = selectedDoctor.id;
        document.getElementById('form_starts_at').value = selectedSlot.start;
        document.getElementById('form_ends_at').value = selectedSlot.end;
    }
});
</script>
@endsection

@endsection
