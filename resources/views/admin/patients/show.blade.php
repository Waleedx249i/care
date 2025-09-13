@extends('layouts.app')

@section('content')
<main class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="h5">{{ $patient->name }} <small class="text-muted">{{ $patient->code }}</small></h2>
            <div class="small text-muted">{{ $patient->gender }} • {{ $patient->phone }} • العمر: {{ $patient->birth_date ? \Carbon\Carbon::parse($patient->birth_date)->age : '-' }}</div>
            <div class="small text-muted">{{ $patient->address }}</div>
        </div>
        <div class="text-end">
            <a href="{{ route('admin.patients.index') }}" class="btn btn-link">رجوع</a>
            <button class="btn btn-success" id="startVisitBtn">بدء زيارة</button>
        </div>
    </div>

    <ul class="nav nav-tabs" id="patientTabs" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#overview" type="button">نظرة عامة</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#appointments" type="button">المواعيد</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#records" type="button">السجلات الطبية</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#prescriptions" type="button">الوصفات</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#billing" type="button">الفوترة</button></li>
    </ul>

    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="overview">
            <div class="row gy-3">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">الموعد القادم</div>
                        <div class="card-body">
                            @php $next = $patient->appointments()->where('starts_at','>',now())->orderBy('starts_at')->first(); @endphp
                            @if($next)
                                <div>{{ $next->starts_at->format('Y-m-d H:i') }} مع {{ $next->doctor->name ?? '' }}</div>
                                <div class="small text-muted">الحالة: {{ $next->status }}</div>
                            @else
                                <div class="text-muted">لا يوجد مواعيد قادمة</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">الفواتير المستحقة</div>
                        <div class="card-body">
                            @php $invoices = $patient->invoices()->where('status','!=','paid')->get(); @endphp
                            @if($invoices->isEmpty())
                                <div class="text-muted">لا توجد فواتير مستحقة</div>
                            @else
                                <ul class="list-group">
                                    @foreach($invoices as $inv)
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>#{{ $inv->id }} • {{ number_format($inv->net_total,2) }}</div>
                                            <div class="small text-muted">{{ $inv->status }}</div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="appointments">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr><th>التاريخ</th><th>الطبيب</th><th>الحالة</th><th>إجراءات</th></tr>
                    </thead>
                    <tbody>
                        @foreach($patient->appointments()->with('doctor.user')->orderByDesc('starts_at')->get() as $a)
                            <tr>
                                <td>{{ $a->starts_at->format('Y-m-d H:i') }} - {{ $a->ends_at->format('H:i') }}</td>
                                <td>{{ $a->doctor->name ?? '' }}</td>
                                <td>{{ $a->status }}</td>
                                <td>
                                    <a href="{{ route('admin.appointments.edit', $a->id) }}" class="btn btn-sm btn-outline-secondary">تعديل</a>
                                    <form method="POST" action="{{ route('admin.appointments.destroy', $a->id) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد؟')">إلغاء</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="records">
            <div class="list-group">
                @foreach($patient->medicalRecords as $r)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fw-bold">{{ optional($r->visit_date)->format('Y-m-d H:i') }} • {{ $r->doctor->name ?? '-' }}</div>
                                <div class="small text-muted">{{ Str::limit($r->diagnosis,80) }}</div>
                            </div>
                            <div>
                                <a href="#" class="btn btn-sm btn-outline-primary">عرض</a>
                                <button class="btn btn-sm btn-secondary" data-bs-toggle="collapse" data-bs-target="#attach-{{ $r->id }}">مرفقات ({{ count($r->attachments ?? []) }})</button>
                            </div>
                        </div>
                        <div class="collapse mt-2" id="attach-{{ $r->id }}">
                            <form action="{{ route('admin.medical_records.attachments', $r->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-2">
                                    <input type="file" name="files[]" multiple class="form-control">
                                </div>
                                <button class="btn btn-sm btn-primary">رفع</button>
                            </form>
                            <div class="mt-2">
                                @foreach($r->attachments ?? [] as $file)
                                    <div><a href="{{ asset('storage/'.$file) }}" target="_blank">{{ basename($file) }}</a></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="tab-pane fade" id="prescriptions">
            @foreach($patient->medicalRecords as $r)
                <div class="card mb-2">
                    <div class="card-header">وصفات {{ optional($r->visit_date)->format('Y-m-d') }}</div>
                    <div class="card-body">
                        @foreach($r->prescriptions as $pres)
                            <div class="mb-2">
                                <div class="fw-bold">{{ $pres->drug_name }} — {{ $pres->dosage }}</div>
                                <div class="small text-muted">التكرار: {{ $pres->frequency }} • المدة: {{ $pres->duration }}</div>
                                <div class="small">{{ $pres->notes }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="tab-pane fade" id="billing">
            <div class="list-group">
                @foreach($patient->invoices as $inv)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">فاتورة #{{ $inv->id }} • {{ number_format($inv->net_total,2) }}</div>
                            <div class="small text-muted">حالة: {{ $inv->status }} • استحقاق: {{ optional($inv->due_date)->format('Y-m-d') }}</div>
                        </div>
                        <div>
                            <a href="#" class="btn btn-sm btn-outline-primary">عرض</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
    document.getElementById('startVisitBtn').addEventListener('click', function(){
        // create medical record with current doctor (if any) or null
        fetch('{{ route('admin.medical_records.store') }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({ patient_id: {{ $patient->id }}, visit_date: new Date().toISOString() })
        }).then(()=> location.reload());
    });
    </script>

@endsection
