@extends('layouts.app')

@section('content')
<main class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5">محرر السجل الطبي</h2>
        <div>
            <a href="{{ route('admin.patients.show', $record->patient_id) }}" class="btn btn-link">رجوع</a>
        </div>
    </div>

    <form id="mrForm" method="POST" action="{{ $record->exists ? route('admin.medical_records.update',$record->id) : route('admin.medical_records.store') }}" enctype="multipart/form-data">
        @csrf
        @if($record->exists) @method('PUT') @endif
        <input type="hidden" name="patient_id" value="{{ $record->patient_id }}">
        <input type="hidden" name="doctor_id" value="{{ $record->doctor_id }}">

        <div class="row g-3">
            <div class="col-12 col-md-4">
                <div class="card p-3">
                    <div class="mb-2"><strong>المريض</strong><div>{{ $record->patient->name ?? '-' }} <small class="text-muted">{{ $record->patient->code ?? '' }}</small></div></div>
                    <div class="mb-2"><strong>الطبيب</strong><div>{{ $record->doctor->name ?? '—' }}</div></div>
                    <div class="mb-2"><label class="form-label">تاريخ الزيارة</label><input type="datetime-local" name="visit_date" value="{{ old('visit_date', $record->visit_date?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}" class="form-control"></div>
                    <div class="mb-2"><label class="form-label">تشخيص</label><textarea name="diagnosis" class="form-control" rows="4">{{ old('diagnosis', $record->diagnosis) }}</textarea></div>
                    <div class="mb-2"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="4">{{ old('notes', $record->notes) }}</textarea></div>
                </div>
            </div>

            <div class="col-12 col-md-8">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">الوصفات الطبية</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addPrescription">إضافة وصفة</button>
                    </div>
                    <div id="prescriptionsContainer">
                        @php $prescriptions = old('prescriptions', $record->prescriptions->toArray() ?? []); @endphp
                        @foreach($prescriptions as $i => $pres)
                            <div class="prescription-row card mb-2 p-2" data-index="{{ $i }}">
                                <div class="row g-2">
                                    <div class="col-6 col-md-4"><input name="prescriptions[{{ $i }}][drug_name]" class="form-control" value="{{ $pres['drug_name'] ?? '' }}" placeholder="الدواء"></div>
                                    <div class="col-6 col-md-2"><input name="prescriptions[{{ $i }}][dosage]" class="form-control" value="{{ $pres['dosage'] ?? '' }}" placeholder="الجرعة"></div>
                                    <div class="col-6 col-md-2"><input name="prescriptions[{{ $i }}][frequency]" class="form-control" value="{{ $pres['frequency'] ?? '' }}" placeholder="التكرار"></div>
                                    <div class="col-6 col-md-2"><input name="prescriptions[{{ $i }}][duration]" class="form-control" value="{{ $pres['duration'] ?? '' }}" placeholder="المدة"></div>
                                    <div class="col-12 col-md-2 text-end"><button type="button" class="btn btn-sm btn-danger remove-prescription">حذف</button></div>
                                    <div class="col-12"><textarea name="prescriptions[{{ $i }}][notes]" class="form-control mt-2" placeholder="ملاحظات">{{ $pres['notes'] ?? '' }}</textarea></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <h6>مرفقات</h6>
                        <input type="file" id="attachments" name="files[]" multiple class="form-control mb-2">
                        <div id="attachmentsPreview">
                            @foreach($record->attachments ?? [] as $file)
                                <div class="mb-1"><a href="{{ asset('storage/'.$file) }}" target="_blank">{{ basename($file) }}</a></div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div id="autosaveIndicator" class="text-muted small">حالة: جاهز</div>
                        <div>
                            <button type="button" class="btn btn-outline-secondary" id="saveDraft">حفظ مسودة</button>
                            <button type="submit" class="btn btn-primary" id="finalizeBtn">تأكيد وحفظ</button>
                            <button type="button" class="btn btn-outline-secondary" id="printBtn">طباعة الملخص</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <template id="presTemplate">
        <div class="prescription-row card mb-2 p-2" data-index="__IDX__">
            <div class="row g-2">
                <div class="col-6 col-md-4"><input name="prescriptions[__IDX__][drug_name]" class="form-control" placeholder="الدواء"></div>
                <div class="col-6 col-md-2"><input name="prescriptions[__IDX__][dosage]" class="form-control" placeholder="الجرعة"></div>
                <div class="col-6 col-md-2"><input name="prescriptions[__IDX__][frequency]" class="form-control" placeholder="التكرار"></div>
                <div class="col-6 col-md-2"><input name="prescriptions[__IDX__][duration]" class="form-control" placeholder="المدة"></div>
                <div class="col-12 col-md-2 text-end"><button type="button" class="btn btn-sm btn-danger remove-prescription">حذف</button></div>
                <div class="col-12"><textarea name="prescriptions[__IDX__][notes]" class="form-control mt-2" placeholder="ملاحظات"></textarea></div>
            </div>
        </div>
    </template>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const presContainer = document.getElementById('prescriptionsContainer');
    const presTemplate = document.getElementById('presTemplate').innerHTML;
    let idx = presContainer.querySelectorAll('.prescription-row').length;

    document.getElementById('addPrescription').addEventListener('click', function(){
        const html = presTemplate.replace(/__IDX__/g, idx);
        presContainer.insertAdjacentHTML('beforeend', html);
        idx++;
    });

    presContainer.addEventListener('click', function(e){
        if(e.target.classList.contains('remove-prescription')){
            e.target.closest('.prescription-row').remove();
        }
    });

    // attachments preview
    document.getElementById('attachments').addEventListener('change', function(e){
        const preview = document.getElementById('attachmentsPreview');
        preview.innerHTML = '';
        Array.from(this.files).forEach(f=>{
            const div = document.createElement('div');
            div.textContent = f.name + ' (' + Math.round(f.size/1024) + ' KB)';
            preview.appendChild(div);
        });
    });

    // Save Draft: submit but remain on page
    document.getElementById('saveDraft').addEventListener('click', function(){
        const form = document.getElementById('mrForm');
        const data = new FormData(form);
        fetch(form.action, { method: form.method || 'POST', headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'}, body: data })
            .then(()=>{ document.getElementById('autosaveIndicator').textContent = 'مسودة محفوظة'; setTimeout(()=>document.getElementById('autosaveIndicator').textContent='جاهز',2000); })
            .catch(()=> alert('خطأ في الحفظ'));
    });

    // Print summary
    document.getElementById('printBtn').addEventListener('click', function(){
        window.print();
    });
});
</script>
@endpush

@endsection
