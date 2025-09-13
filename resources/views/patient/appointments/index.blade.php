@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>My Appointments</h3>
        <form class="d-flex" method="get">
            <select name="status" class="form-select me-2">
                <option value="">All</option>
                <option value="upcoming" {{ request('status')=='upcoming'?'selected':'' }}>Upcoming</option>
                <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
                <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
            </select>
            <button class="btn btn-outline-primary">Filter</button>
        </form>
    </div>

    <div class="table-responsive d-none d-md-block">
        <table class="table table-striped">
            <thead><tr><th>Date</th><th>Time</th><th>Doctor</th><th>Specialization</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($appointments as $a)
                    <tr>
                        <td>{{ $a->starts_at->toDateString() }}</td>
                        <td>{{ $a->starts_at->format('H:i') }} - {{ $a->ends_at->format('H:i') }}</td>
                        <td>{{ $a->doctor->name }}</td>
                        <td>{{ $a->doctor->specialty }}</td>
                        <td><span class="badge bg-{{ $a->status=='cancelled' ? 'secondary' : ($a->status=='pending' ? 'warning' : ($a->status=='completed'?'success':'info')) }}">{{ ucfirst($a->status) }}</span></td>
                        <td>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ url('/patient/appointments/'.$a->id) }}">View Details</a>
                            <a class="btn btn-sm btn-outline-primary" href="{{ url('/appointments/book?appointment_id='.$a->id) }}">Reschedule</a>
                            @if($a->status!='cancelled')
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal" data-appt-id="{{ $a->id }}">Cancel</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-block d-md-none">
        <div class="row">
            @foreach($appointments as $a)
                <div class="col-12 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="fw-bold">{{ $a->starts_at->toDayDateTimeString() }}</div>
                                    <div class="text-muted">Dr. {{ $a->doctor->name }} — {{ $a->doctor->specialty }}</div>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $a->status=='cancelled' ? 'secondary' : ($a->status=='pending' ? 'warning' : ($a->status=='completed'?'success':'info')) }}">{{ ucfirst($a->status) }}</span>
                                </div>
                            </div>
                            <div class="mt-2 d-flex gap-2">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ url('/patient/appointments/'.$a->id) }}">View</a>
                                <a class="btn btn-sm btn-outline-primary" href="{{ url('/appointments/book?appointment_id='.$a->id) }}">Reschedule</a>
                                @if($a->status!='cancelled')
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal" data-appt-id="{{ $a->id }}">Cancel</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{ $appointments->links() }}

    <!-- Cancel modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form method="post" id="cancelForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Cancel Appointment</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">Are you sure you want to cancel this appointment?</div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Yes, cancel</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                </div>
            </div>
        </form>
      </div>
    </div>

</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    var cancelModal = document.getElementById('cancelModal');
    cancelModal.addEventListener('show.bs.modal', function(e){
        var btn = e.relatedTarget;
        var id = btn.getAttribute('data-appt-id');
        var form = document.getElementById('cancelForm');
        form.action = '/patient/appointments/' + id + '/cancel';
    });
});
</script>
@endsection

@endsection
