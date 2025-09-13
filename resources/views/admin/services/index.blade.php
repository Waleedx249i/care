@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-list-task me-2"></i> إدارة الخدمات</h3>
        <button class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#addService">
            <i class="bi bi-plus-circle me-1"></i> إضافة خدمة
        </button>
    </div>

    <!-- Add Service Form -->
    <div id="addService" class="collapse mb-4">
        <div class="card card-body bg-light border rounded">
            <form method="POST" action="{{ route('admin.services.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12 col-md-5">
                        <label class="form-label">اسم الخدمة</label>
                        <input name="name" class="form-control" placeholder="مثال: كشف طبيب أسنان" required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">السعر (ر.س)</label>
                        <input name="price" type="number" step="0.01" min="0" class="form-control" placeholder="0.00" required>
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <div class="form-check">
                            <input name="active" class="form-check-input" type="checkbox" id="activeDefault" checked>
                            <label class="form-check-label" for="activeDefault">مفعل</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">حفظ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Desktop Table -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>السعر</th>
                    <th>الحالة</th>
                    <th class="text-center">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $s)
                <tr>
                    <td class="fw-bold">#{{ $s->id }}</td>
                    <td>{{ $s->name }}</td>
                    <td class="fw-bold text-success">{{ number_format($s->price, 2) }} ر.س</td>
                    <td>
                        <div class="form-check form-switch">
                            <input 
                                type="checkbox" 
                                class="form-check-input service-toggle" 
                                data-id="{{ $s->id }}" 
                                {{ $s->active ? 'checked' : '' }}
                                style="cursor: pointer;"
                            >
                            <label class="form-check-label text-muted small">
                                {{ $s->active ? 'مفعل' : 'معطل' }}
                            </label>
                        </div>
                    </td>
                    <td class="text-end">
                        <button 
                            class="btn btn-sm btn-warning px-3 me-1" 
                            onclick="openDesktopEdit({{ $s->id }}, '{{ addslashes($s->name) }}', {{ $s->price }}, {{ $s->active ? 'true' : 'false' }})">
                            <i class="bi bi-pencil"></i> تعديل
                        </button>
                        <form method="POST" action="{{ route('admin.services.destroy', $s->id) }}" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger px-3">
                                <i class="bi bi-trash"></i> حذف
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">لا توجد خدمات مسجلة.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Desktop Edit Modal (Hidden Form) -->
    <div id="desktopEditForm" class="d-none">
        <form method="POST" action="" id="editServiceForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_id">
            <div class="row g-2 mt-2">
                <div class="col-md-5">
                    <input name="name" id="edit_name" class="form-control form-control-sm" placeholder="اسم الخدمة" required>
                </div>
                <div class="col-md-4">
                    <input name="price" id="edit_price" type="number" step="0.01" min="0" class="form-control form-control-sm" placeholder="السعر" required>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <div class="form-check">
                        <input name="active" id="edit_active" class="form-check-input" type="checkbox">
                        <label class="form-check-label">مفعل</label>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100">حفظ</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Mobile Cards -->
    <div class="d-md-none">
        @forelse($services as $s)
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1 fw-bold">{{ $s->name }}</h6>
                        <div class="small text-success fw-bold">{{ number_format($s->price, 2) }} ر.س</div>
                    </div>
                    <div class="text-end">
                        <div class="form-check form-switch mb-2">
                            <input 
                                class="form-check-input mobile-toggle" 
                                type="checkbox" 
                                data-id="{{ $s->id }}" 
                                {{ $s->active ? 'checked' : '' }}
                                style="cursor: pointer;"
                            >
                        </div>
                        <button class="btn btn-sm btn-outline-secondary" onclick="openMobileEdit({{ $s->id }}, '{{ addslashes($s->name) }}', {{ $s->price }}, {{ $s->active ? 'true' : 'false' }})">
                            <i class="bi bi-pencil"></i> تعديل
                        </button>
                        <form method="POST" action="{{ route('admin.services.destroy', $s->id) }}" class="d-inline-block mt-1" onsubmit="return confirm('حذف؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i> حذف
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile Edit Form -->
                <div id="mobile-edit-{{ $s->id }}" class="mt-3 p-3 bg-light rounded d-none">
                    <form method="POST" action="{{ route('admin.services.update', $s->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-2">
                            <div class="col-12">
                                <input name="name" class="form-control form-control-sm" value="{{ $s->name }}" placeholder="اسم الخدمة" required>
                            </div>
                            <div class="col-6">
                                <input name="price" type="number" step="0.01" min="0" class="form-control form-control-sm" value="{{ $s->price }}" placeholder="السعر" required>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input name="active" class="form-check-input" type="checkbox" {{ $s->active ? 'checked' : '' }}>
                                    <label class="form-check-label small">مفعل</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-sm btn-primary w-100">حفظ</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-list-task" style="font-size: 2rem;"></i>
            <div class="mt-2">لا توجد خدمات</div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($services->hasPages())
        <div class="mt-4">
            {{ $services->links() }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Toggle Active Status (Desktop & Mobile)
    document.querySelectorAll('.service-toggle, .mobile-toggle').forEach(cb => {
        cb.addEventListener('change', async function() {
            const id = this.dataset.id;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(`/admin/services/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    this.checked = !this.checked; // Revert if failed
                    alert('فشل في تحديث الحالة');
                }
            } catch (error) {
                this.checked = !this.checked;
                alert('خطأ في الاتصال');
            }
        });
    });

    // Open Desktop Edit (injects form above table)
    function openDesktopEdit(id, name, price, active) {
        const formContainer = document.getElementById('desktopEditForm');
        const form = document.getElementById('editServiceForm');
        
        // Set form action
        form.action = `/admin/services/${id}`;
        
        // Fill data
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_active').checked = active;

        // Inject form after table or before pagination
        const table = document.querySelector('.table');
        if (table) {
            table.parentNode.insertBefore(formContainer, table.nextSibling);
            formContainer.classList.remove('d-none');
        }
    }

    // Open Mobile Edit
    function openMobileEdit(id, name, price, active) {
        const el = document.getElementById(`mobile-edit-${id}`);
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endsection