@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Medical Record</h3>
    <div class="card mb-3">
        <div class="card-body">
            <div><strong>Date:</strong> {{ optional($medicalRecord->visit_date)->toDayDateTimeString() }}</div>
            <div><strong>Doctor:</strong> Dr. {{ $medicalRecord->doctor->name }}</div>
            <hr>
            <h5>Diagnosis</h5>
            <div>{{ $medicalRecord->diagnosis }}</div>
            <h5 class="mt-3">Notes</h5>
            <div>{{ $medicalRecord->notes }}</div>
            <h5 class="mt-3">Attachments</h5>
            @if(!empty($medicalRecord->attachments))
                <ul>
                @foreach($medicalRecord->attachments as $att)
                    <li><a href="{{ asset('storage/'.$att) }}" download>{{ basename($att) }}</a></li>
                @endforeach
                </ul>
            @else
                <div class="text-muted">No attachments</div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">Prescriptions</div>
        <div class="card-body">
            @if($medicalRecord->prescriptions->isEmpty())
                <div class="text-muted">No prescriptions</div>
            @else
                <ul class="list-group">
                    @foreach($medicalRecord->prescriptions as $p)
                        <li class="list-group-item">
                            <div><strong>{{ optional($p->created_at)->toDateString() }}</strong></div>
                            <div>{{ $p->notes ?? '' }}</div>
                            <div class="text-muted">{{ implode(', ', $p->medicines ?? []) }}</div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

@endsection
