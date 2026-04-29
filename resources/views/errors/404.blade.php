{{-- ✅ المسار: resources/views/errors/404.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6 text-center">

            {{-- ✅ بطاقة الخطأ --}}
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                {{-- ✅ رأس البطاقة --}}
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
                             alt="Page non trouvée"
                             class="img-fluid"
                             style="max-height: 180px;"
                             onerror="this.src='https://via.placeholder.com/180x180?text=404'">
                    </div>

                    {{-- ✅ العناوين --}}
                    <h2 class="fw-bold mb-3 text-dark">Page non trouvée</h2>
                    <p class="lead text-muted mb-4">
                        Désolé, la page que vous cherchez n'existe pas ou a été déplacée.
                    </p>

                    {{-- ✅ أزرار الإجراءات --}}
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-4">
                        <a href="{{ url('/') }}" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-home me-2"></i>Accueil
                        </a>
                        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>

                    {{-- ✅ شريط بحث اختياري
                    <form action="{{ route('search') }}" method="GET" class="mt-4">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Rechercher...">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form> --}}

                </div> {{-- ✅ إغلاق .card-body --}}
            </div> {{-- ✅ إغلاق .card --}}
        </div> {{-- ✅ إغلاق .col --}}
    </div> {{-- ✅ إغلاق .row --}}
</div> {{-- ✅ إغلاق .container --}}
@endsection

{{-- ✅ التنسيقات - خارج section content --}}
@push('styles')
<style>
    .bg-gradient-danger {
        background: linear-gradient(135deg, #e74a3b 0%, #c0392b 100%);
    }
    .card { animation: slideUp 0.4s ease-out; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes shake {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-10deg); }
        75% { transform: rotate(10deg); }
    }
    .animate__shakeX { animation: shake 0.5s ease-in-out; }
    .particles {
        background-image:
            radial-gradient(rgba(255,255,255,0.3) 1px, transparent 1px),
            radial-gradient(rgba(255,255,255,0.2) 1px, transparent 1px);
        background-size: 20px 20px, 30px 30px;
        background-position: 0 0, 15px 15px;
        opacity: 0.3;
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
    @media (max-width: 576px) {
        .display-1 { font-size: 4rem; }
        .card-body { padding: 2rem !important; }
    }
</style>
@endpush

{{-- ✅ السكربتات - خارج section content --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تأثير دخول البطاقة
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

    // تحسين حقل البحث
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
