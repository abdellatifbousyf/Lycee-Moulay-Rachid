{{-- ✅ المسار: resources/views/errors/404.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6 text-center">

            {{-- ✅ بطاقة الخطأ --}}
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                {{-- ✅ رأس البطاقة مع تأثير --}}
                <div class="card-header bg-gradient-danger text-white border-0 py-4">
                    <div class="position-relative">
                        <i class="fas fa-exclamation-triangle fa-4x mb-3 animate__animated animate__shakeX"></i>
                        <h1 class="display-1 fw-bold mb-0 lh-1">404</h1>
                        <div class="position-absolute top-0 start-0 w-100 h-100">
                            <div class="particles"></div>
                        </div>
                    </div>
                </div>

                {{-- ✅ محتوى البطاقة --}}
                <div class="card-body p-4 p-md-5">

                    {{-- ✅ أيقونة توضيحية --}}
                    <div class="mb-4">
                        <img src="https://cdn-icons-png.flaticon.com/512/404/404430.png"
                             alt="Page not found"
                             class="img-fluid"
                             style="max-height: 180px;"
                             onerror="this.src='https://via.placeholder.com/180x180?text=404'">
                    </div>

                    {{-- ✅ العناوين --}}
                    <h2 class="fw-bold mb-3 text-dark">Page non trouvée</h2>

                    <p class="lead text-muted mb-4">
                        Désolé, la page que vous cherchez n'existe pas ou a été déplacée.
                    </p>

                    {{-- ✅ شريط بحث سريع --}}
                    <div class="mb-4">
                        <form action="{{ url('/search') }}" method="GET" class="input-group">
                            <input type="text"
                                   name="q"
                                   class="form-control border-end-0"
                                   placeholder="Rechercher une page..."
                                   aria-label="Rechercher">
                            <button class="btn btn-outline-secondary border-start-0" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                    {{-- ✅ روابط مفيدة --}}
                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <a href="{{ url('/') }}" class="btn btn-primary w-100 py-2">
                                <i class="fas fa-home me-1"></i> Accueil
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="javascript:history.back()" class="btn btn-outline-secondary w-100 py-2">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </div>

                    {{-- ✅ قائمة صفحات شائعة --}}
                    <div class="border-top pt-3">
                        <small class="text-muted d-block mb-2">Pages populaires :</small>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('etudiant.absences') }}" class="badge bg-light text-dark text-decoration-none p-2 border">
                                <i class="fas fa-list me-1"></i> Absences
                            </a>
                            <a href="{{ route('etudiant.notes') }}" class="badge bg-light text-dark text-decoration-none p-2 border">
                                <i class="fas fa-chart-bar me-1"></i> Notes
                            </a>
                            <a href="{{ route('etudiant.emploi') }}" class="badge bg-light text-dark text-decoration-none p-2 border">
                                <i class="fas fa-calendar me-1"></i> Emploi du temps
                            </a>
                        </div>
                    </div>

                </div>

                {{-- ✅ تذييل البطاقة --}}
                <div class="card-footer bg-light border-0 py-3">
                    <small class="text-muted">
                        <i class="fas fa-code me-1"></i>
                        Erreur 404 •
                        <a href="{{ url('/contact') }}" class="text-decoration-none">Signaler un problème</a>
                    </small>
                </div>
            </div>

            {{-- ✅ نص إضافي أسفل البطاقة --}}
            <p class="mt-4 text-muted small">
                Vous êtes perdu ?
                <a href="{{ url('/help') }}" class="text-decoration-none fw-bold">Contactez le support</a>
            </p>

        </div>
    </div>
</div>

{{-- ✅ التنسيقات الإضافية --}}
@push('styles')
<style>
    /* تدرج لوني للخطر */
    .bg-gradient-danger {
        background: linear-gradient(135deg, #e74a3b 0%, #c0392b 100%);
    }

    /* تأثيرات البطاقة */
    .card {
        animation: slideUp 0.4s ease-out;
        transition: transform 0.2s;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* تأثير الاهتزاز للأيقونة */
    @keyframes shake {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-10deg); }
        75% { transform: rotate(10deg); }
    }

    .animate__shakeX {
        animation: shake 0.5s ease-in-out;
    }

    /* تأثير الجسيمات الخلفية */
    .particles {
        background-image:
            radial-gradient(rgba(255,255,255,0.3) 1px, transparent 1px),
            radial-gradient(rgba(255,255,255,0.2) 1px, transparent 1px);
        background-size: 20px 20px, 30px 30px;
        background-position: 0 0, 15px 15px;
        opacity: 0.3;
    }

    /* تحسينات الحقول */
    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(231, 74, 59, 0.25);
        border-color: #e74a3b;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border: none;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
    }

    /* تأثيرات الروابط */
    .badge {
        transition: all 0.2s;
        cursor: pointer;
    }

    .badge:hover {
        background: #4e73df !important;
        color: #fff !important;
        transform: translateY(-2px);
    }

    /* تحسينات للشاشات الصغيرة */
    @media (max-width: 576px) {
        .display-1 {
            font-size: 4rem;
        }
        .card-body {
            padding: 2rem !important;
        }
    }
</style>
@endpush

{{-- ✅ السكربتات الإضافية --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تأثير بسيط عند تحميل الصفحة
        const card = document.querySelector('.card');
        if (card) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.4s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }

        // منع إرسال الفورم إذا كان الحقل فارغاً
        const searchForm = document.querySelector('form[action*="search"]');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const input = this.querySelector('input[name="q"]');
                if (input && !input.value.trim()) {
                    e.preventDefault();
                    input.focus();
                    input.classList.add('is-invalid');
                    setTimeout(() => input.classList.remove('is-invalid'), 2000);
                }
            });
        }
    });
</script>
@endpush
@endsection
