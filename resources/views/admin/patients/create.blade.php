@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-person-plus me-2"></i> إضافة مريض جديد</h3>
        <a href="{{ route('admin.patients.index') }}" class="btn btn-sm btn-outline-secondary">العودة للقائمة</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form id="createPatientForm">
                @csrf

                <div class="row g-3">
                    <!-- Patient Fields -->
                    <div class="col-12 col-md-6">
                        <label class="form-label">كود المريض <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">الجنس</label>
                        <select name="gender" class="form-select">
                            <option value="">غير محدد</option>
                            <option value="male">ذكر</option>
                            <option value="female">أنثى</option>
                            <option value="other">آخر</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">تاريخ الميلاد</label>
                        <input type="date" name="birth_date" class="form-control">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">رقم الهاتف</label>
                        <input type="tel" name="phone" class="form-control" placeholder="+966...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">العنوان</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="col-12 border-top pt-3 mt-3">
                        <h5 class="mb-3"><i class="bi bi-person-gear me-2"></i> إنشاء حساب مستخدم (اختياري)</h5>
                        <div class="form-check mb-3">
                            <input type="checkbox" id="createUser" class="form-check-input">
                            <label class="form-check-label" for="createUser">إنشاء حساب مستخدم لهذا المريض</label>
                        </div>

                        <div id="userFields" class="row g-3 d-none">
                            <div class="col-12 col-md-6">
                                <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control">
                                    <button type="button" class="btn btn-outline-secondary" id="generatePassword">توليد</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4">حفظ المريض</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('createUser');
    const userFields = document.getElementById('userFields');
    const passwordInput = document.querySelector('input[name="password"]');
    const generateBtn = document.getElementById('generatePassword');

    // Toggle User Fields
    toggle.addEventListener('change', function() {
        userFields.classList.toggle('d-none', !this.checked);
        if (this.checked) {
            document.querySelector('input[name="email"]').setAttribute('required', true);
            passwordInput.setAttribute('required', true);
        } else {
            document.querySelector('input[name="email"]').removeAttribute('required');
            passwordInput.removeAttribute('required');
        }
    });

    // Generate Random Password
    generateBtn.addEventListener('click', function() {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        let password = '';
        for (let i = 0; i < 12; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        passwordInput.value = password;
    });

    // Handle Form Submission
    document.getElementById('createPatientForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        if (!toggle.checked) {
            formData.delete('email');
            formData.delete('password');
        }

        try {
            const response = await fetch('{{ route('admin.patients.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (response.ok) {
                window.location.href = '{{ url('/admin/patients') }}/' + result.id;
            } else {
                alert('حدث خطأ: ' + (result.message || 'يرجى المحاولة لاحقًا'));
            }
        } catch (error) {
            alert('خطأ في الاتصال بالخادم');
        }
    });
});
</script>
@endsection