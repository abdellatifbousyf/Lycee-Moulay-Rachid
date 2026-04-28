{{-- ✅ المسار: resources/views/Etudiant/EspaceEtudiant.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">

            {{-- ✅ بطاقة الترحيب بالطالب --}}
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-gradient-primary text-white py-3 rounded-top-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-graduation-cap fa-2x me-3"></i>
                        <h4 class="mb-0 fw-bold">{{ __('Dashboard Etudiant') }}</h4>
                    </div>
                </div>

                <div class="card-body p-4">

                    {{-- ✅ رسالة النجاح مع زر إغلاق --}}
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- ✅ قسم معلومات الطالب --}}
                    <div class="text-center mb-4">
                        <div class="avatar-container mb-3">
                            <img src="{{ asset('images/student-avatar.png') }}"
                                 alt="Student"
                                 class="rounded-circle shadow"
                                 width="100"
                                 height="100"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4e73df&color=fff&size=100'">
                        </div>
                        <h5 class="fw-bold mb-1">{{ Auth::user()->name ?? __('Student') }}</h5>
                        <p class="text-muted mb-0">{{ Auth::user()->email ?? '' }}</p>
                        <span class="badge bg-success mt-2">
                            <i class="fas fa-circle fa-xs me-1"></i>
                            {{ __('You are logged in!') }}
                        </span>
                    </div>

                    {{-- ✅ بطاقات الإحصائيات السريعة --}}
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="card bg-primary bg-gradient text-white h-100 border-0 shadow-sm">
                                <div class="card-body text-center py-3">
                                    <i class="fas fa-calendar-check fa-2x mb-2"></i>
                                    <h6 class="mb-0 fw-bold">الحضور</h6>
                                    <span class="fs-5 fw-bold">95%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-success bg-gradient text-white h-100 border-0 shadow-sm">
                                <div class="card-body text-center py-3">
                                    <i class="fas fa-chart-line fa-2x mb-2"></i>
                                    <h6 class="mb-0 fw-bold">المعدل</h6>
                                    <span class="fs-5 fw-bold">16.5/20</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-info bg-gradient text-white h-100 border-0 shadow-sm">
                                <div class="card-body text-center py-3">
                                    <i class="fas fa-book fa-2x mb-2"></i>
                                    <h6 class="mb-0 fw-bold">المواد</h6>
                                    <span class="fs-5 fw-bold">8</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-warning bg-gradient text-white h-100 border-0 shadow-sm">
                                <div class="card-body text-center py-3">
                                    <i class="fas fa-bell fa-2x mb-2"></i>
                                    <h6 class="mb-0 fw-bold">تنبيهات</h6>
                                    <span class="fs-5 fw-bold">3</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ✅ أزرار الوصول السريع --}}
                    <div class="d-grid gap-2">
                        <a href="{{ route('etudiant.absences') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list-alt me-2"></i>
                            {{ __('سجل الغياب') }}
                        </a>
                        <a href="{{ route('etudiant.notes') }}" class="btn btn-outline-success">
                            <i class="fas fa-poll me-2"></i>
                            {{ __('عرض النقط') }}
                        </a>
                        <a href="{{ route('etudiant.emploi') }}" class="btn btn-outline-info">
                            <i class="fas fa-calendar-week me-2"></i>
                            {{ __('جدول الحصص') }}
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- ✅ تنسيقات إضافية --}}
@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }
    .card {
        transition: transform 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    .avatar-container {
        position: relative;
        display: inline-block;
    }
    .avatar-container::after {
        content: '';
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 15px;
        height: 15px;
        background: #28a745;
        border: 2px solid #fff;
        border-radius: 50%;
    }
    .btn-outline-primary:hover,
    .btn-outline-success:hover,
    .btn-outline-info:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.2s;
    }
</style>
@endpush

{{-- ✅ مكتبة الأيقونات (إذا لم تكن مضافة مسبقاً) --}}
@push('scripts')
<script>
    // تحسين إغلاق التنبيهات
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.addEventListener('closed.bs.alert', function() {
                // يمكن إضافة كود هنا لحفظ حالة الإغلاق
            });
        });
    });
</script>
@endpush
@endsection
