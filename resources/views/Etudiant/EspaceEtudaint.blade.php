{{-- ✅ المسار: resources/views/Etudiant/EspaceEtudiant.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">

            {{-- ✅ بطاقة الترحيب --}}
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-gradient-primary text-white py-3 rounded-top-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-graduation-cap fa-2x me-3"></i>
                        <h4 class="mb-0 fw-bold">{{ __('Dashboard Etudiant') }}</h4>
                    </div>
                </div>

                <div class="card-body p-4">

                    {{-- ✅ رسائل التنبيه --}}
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- ✅ معلومات الطالب --}}
                    <div class="text-center mb-4">
                        <div class="avatar-container mb-3">
                            <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=4e73df&color=fff&size=100' }}"
                                 alt="{{ Auth::user()->name }}"
                                 class="rounded-circle shadow"
                                 width="100" height="100"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4e73df&color=fff&size=100'">
                        </div>
                        <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                        <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                        <p class="text-muted small">CNE: {{ Auth::user()->cne ?? 'N/A' }}</p>
                        <span class="badge bg-success mt-2">
                            <i class="fas fa-circle fa-xs me-1"></i>
                            {{ __('En ligne') }}
                        </span>
                    </div>

                    {{-- ✅ إحصائيات ديناميكية من Controller --}}
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <a href="{{ route('etudiant.absences') }}" class="text-decoration-none">
                                <div class="card bg-primary bg-gradient text-white h-100 border-0 shadow-sm hover-lift">
                                    <div class="card-body text-center py-3">
                                        <i class="fas fa-calendar-check fa-2x mb-2"></i>
                                        <h6 class="mb-0 fw-bold">{{ __('الحضور') }}</h6>
                                        <span class="fs-5 fw-bold">{{ $stats['attendance_rate'] ?? 0 }}%</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('etudiant.notes') }}" class="text-decoration-none">
                                <div class="card bg-success bg-gradient text-white h-100 border-0 shadow-sm hover-lift">
                                    <div class="card-body text-center py-3">
                                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                                        <h6 class="mb-0 fw-bold">{{ __('المعدل') }}</h6>
                                        <span class="fs-5 fw-bold">{{ $stats['average'] ?? 'N/A' }}/20</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <div class="card bg-info bg-gradient text-white h-100 border-0 shadow-sm">
                                <div class="card-body text-center py-3">
                                    <i class="fas fa-book fa-2x mb-2"></i>
                                    <h6 class="mb-0 fw-bold">{{ __('المواد') }}</h6>
                                    <span class="fs-5 fw-bold">{{ $stats['subjects_count'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-warning bg-gradient text-white h-100 border-0 shadow-sm">
                                <div class="card-body text-center py-3">
                                    <i class="fas fa-bell fa-2x mb-2"></i>
                                    <h6 class="mb-0 fw-bold">{{ __('تنبيهات') }}</h6>
                                    <span class="fs-5 fw-bold">{{ $notificationsCount ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ✅ أزرار الوصول السريع مع تحقق من الصلاحيات --}}
                    <div class="d-grid gap-2">
                        @if (Route::has('etudiant.absences'))
                        <a href="{{ route('etudiant.absences') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list-alt me-2"></i>
                            {{ __('سجل الغياب') }}
                        </a>
                        @endif

                        @if (Route::has('etudiant.notes'))
                        <a href="{{ route('etudiant.notes') }}" class="btn btn-outline-success">
                            <i class="fas fa-poll me-2"></i>
                            {{ __('عرض النقط') }}
                        </a>
                        @endif

                        @if (Route::has('etudiant.emploi'))
                        <a href="{{ route('etudiant.emploi') }}" class="btn btn-outline-info">
                            <i class="fas fa-calendar-week me-2"></i>
                            {{ __('جدول الحصص') }}
                        </a>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }
    .hover-lift {
        transition: transform 0.2s ease-in-out;
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
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
</style>
@endpush

</body>
</html>
