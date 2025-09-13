@extends('layouts.app')

@section('content')
<div class="container">
    <h3>My Prescriptions</h3>

    @if($grouped->isEmpty())
        <div class="text-center p-4">
            <p class="text-muted">No prescriptions found.</p>
            <a href="/doctor/services" class="btn btn-primary">View Services</a>
        </div>
    @else
        @foreach($grouped as $key => $items)
            @php list($date, $doctor) = explode('|', $key); @endphp
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div><strong>{{ $date }}</strong> — Dr. {{ $doctor }}</div>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">Print</button>
                        <a href="#" class="btn btn-sm btn-outline-primary">Download PDF</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($items as $pres)
                            <div class="col-md-6 col-12 mb-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="fw-bold">{{ $pres->drug_name }}</div>
                                        <div>Dosage: {{ $pres->dosage }}</div>
                                        <div>Frequency: {{ $pres->frequency }}</div>
                                        <div>Duration: {{ $pres->duration }}</div>
                                        <div class="mt-2 text-muted">{{ $pres->notes }}</div>
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">Print</button>
                                            <a href="#" class="btn btn-sm btn-outline-primary">Download PDF</a>
                                            <button class="btn btn-sm btn-outline-info" onclick="setReminder('{{ addslashes($pres->drug_name) }}','{{ addslashes($pres->notes) }}')">Set Reminder</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

@section('scripts')
<script>
function setReminder(title, body){
    if (!('Notification' in window)) return alert('Notifications not supported');
    Notification.requestPermission().then(function(permission){
        if (permission === 'granted'){
            new Notification('Prescription reminder: ' + title, { body: body });
            alert('Reminder set (local notification shown now).');
        }
    });
}
</script>
@endsection

@endsection
