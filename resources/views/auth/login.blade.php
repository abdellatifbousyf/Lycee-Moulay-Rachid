{{-- ✅ المسار: resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Login') }} - {{ config('app.name', '4ayab') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* خلفية متدرجة احترافية */
        .login-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 30%, #60a5fa 70%, #93c5fd 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* تأثير دوائر متحركة في الخلفية */
        .login-page::before,
        .login-page::after {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
        }

        .login-page::before {
            top: -200px;
            right: -200px;
            animation-delay: 0s;
        }

        .login-page::after {
            bottom: -300px;
            left: -100px;
            animation-delay: -10s;
            width: 400px;
            height: 400px;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.95); }
        }

        /* تحسين شكل الكارد */
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border: none;
            border-radius: 20px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
            position: relative;
            z-index: 1;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* شريط علوي ملون للكارد */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4f46e5, #7c3aed, #06b6d4);
        }

        .login-card .card-header {
            background: transparent;
            border: none;
            padding: 2rem 2rem 1rem;
            text-align: center;
        }

        .login-card .card-body {
            padding: 1rem 2rem 2rem;
        }

        .login-card .card-footer {
            background: transparent;
            border: none;
            padding: 1rem 2rem 2rem;
            text-align: center;
        }

        /* عنوان مع شعار */
        .login-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .login-brand-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
        }

        .login-brand-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .login-brand-subtitle {
            font-size: 0.9rem;
            color: #64748b;
            margin: 0;
        }

        /* تحسين الحقول */
        .form-control {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
            background: #f8fafc;
        }

        .form-control:focus {
            border-color: #4f46e5;
            background: white;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .form-group label {
            font-weight: 600;
            color: #334155;
            font-size: 0.9rem;
        }

        /* تحسين الأزرار */
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
        }

        .btn-link {
            color: #4f46e5;
            font-weight: 500;
            text-decoration: none;
        }

        .btn-link:hover {
            color: #7c3aed;
            text-decoration: none;
        }

        /* تحسين رسالة الخطأ */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Checkbox مخصص */
        .form-check-input {
            border-radius: 6px;
            border: 2px solid #cbd5e1;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-card {
                border-radius: 16px;
            }
            .login-brand-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
            .login-brand-title {
                font-size: 1.25rem;
            }
            .col-md-4 { flex: 0 0 100%; max-width: 100%; text-align: left !important; }
            .col-md-8 { flex: 0 0 100%; max-width: 100%; }
            .offset-md-4 { margin-left: 0 !important; }
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card login-card">
                        <div class="card-header">
                            {{-- 🎓 شعار واسم المشروع --}}
                            <div class="login-brand">
                                <div class="login-brand-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <h2 class="login-brand-title">{{ config('app.name', '4ayab') }}</h2>
                                <p class="login-brand-subtitle">{{ __('تسجيل الدخول لمتابعة الغيابات') }}</p>
                            </div>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                {{-- ✅ رسائل الخطأ العامة --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show mb-4">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    </div>
                                @endif

                                {{-- Email --}}
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">
                                        {{ __('E-Mail Address') }} <span class="text-danger">*</span>
                                    </label>

                                    <div class="col-md-8">
                                        <input
                                            id="email"
                                            type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            name="email"
                                            value="{{ old('email') }}"
                                            required
                                            autocomplete="email"
                                            autofocus
                                            placeholder="{{ __('أدخل بريدك الإلكتروني') }}"
                                        >

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Password --}}
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">
                                        {{ __('Password') }} <span class="text-danger">*</span>
                                    </label>

                                    <div class="col-md-8">
                                        <input
                                            id="password"
                                            type="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            name="password"
                                            required
                                            autocomplete="current-password"
                                            placeholder="{{ __('أدخل كلمة المرور') }}"
                                        >

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Remember Me --}}
                                <div class="form-group row">
                                    <div class="col-md-8 offset-md-4">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="remember"
                                                id="remember"
                                                {{ old('remember') ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="remember">
                                                {{ __('تذكرني') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Buttons --}}
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4 d-flex flex-column gap-2">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-sign-in-alt me-2"></i> {{ __('تسجيل الدخول') }}
                                        </button>

                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link text-center" href="{{ route('password.request') }}">
                                                {{ __('نسيت كلمة المرور؟') }}
                                            </a>
                                        @endif

                                        {{-- ✅ رابط التسجيل (يلا كان مفعل) --}}
                                        @if (Route::has('register'))
                                            <a class="btn btn-link text-center" href="{{ route('register') }}">
                                                {{ __('إنشاء حساب جديد') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- ✅ روابط إضافية فـ أسفل الكارد --}}
                        <div class="card-footer">
                            <div class="text-muted small">
                                {{ __('هل تحتاج مساعدة؟') }}
                                <a href="{{ route('contact') ?? '#' }}">{{ __('تواصل مع الدعم') }}</a>
                            </div>
                        </div>
                    </div>

                    {{-- حقوق النشر --}}
                    <div class="text-center mt-4" style="color: rgba(255,255,255,0.8); font-size: 0.85rem;">
                        &copy; {{ date('Y') }} {{ config('app.name', '4ayab') }}. {{ __('جميع الحقوق محفوظة.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
