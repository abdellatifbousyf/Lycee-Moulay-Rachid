{{-- ✅ المسار: resources/views/auth/verify.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Verify Email') }} - {{ config('app.name', '4ayab') }}</title>

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
        .verify-page {
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
        .verify-page::before,
        .verify-page::after {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
        }

        .verify-page::before {
            top: -200px;
            right: -200px;
            animation-delay: 0s;
        }

        .verify-page::after {
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
        .verify-card {
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
        .verify-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4f46e5, #7c3aed, #06b6d4);
        }

        .verify-card .card-header {
            background: transparent;
            border: none;
            padding: 2rem 2rem 1rem;
            text-align: center;
        }

        .verify-card .card-body {
            padding: 1rem 2rem 2rem;
        }

        .verify-card .card-footer {
            background: transparent;
            border: none;
            padding: 1rem 2rem 2rem;
            text-align: center;
        }

        /* عنوان مع شعار */
        .verify-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .verify-brand-icon {
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

        .verify-brand-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .verify-brand-subtitle {
            font-size: 0.9rem;
            color: #64748b;
            margin: 0;
        }

        /* Badge للتحقق */
        .badge-verified {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
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

        .btn-outline-secondary {
            border-radius: 10px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }

        /* تحسين رسائل التنبيه */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border-left: 4px solid #3b82f6;
        }

        /* معلومات المستخدم */
        .user-info {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .user-info i {
            color: #64748b;
            width: 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .verify-card {
                border-radius: 16px;
            }
            .verify-brand-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
            .verify-brand-title {
                font-size: 1.25rem;
            }
            .d-flex {
                flex-direction: column !important;
                gap: 0.75rem !important;
            }
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="verify-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card verify-card">
                        <div class="card-header">
                            {{-- 🔐 شعار واسم المشروع --}}
                            <div class="verify-brand">
                                <div class="verify-brand-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <h2 class="verify-brand-title">{{ config('app.name', '4ayab') }}</h2>
                                <p class="verify-brand-subtitle">{{ __('التحقق من البريد الإلكتروني') }}</p>
                            </div>

                            {{-- ✅ عرض حالة التحقق --}}
                            @if (Auth::user()->hasVerifiedEmail())
                                <div class="mt-3">
                                    <span class="badge-verified">
                                        <i class="fas fa-check"></i> {{ __('تم التحقق ✓') }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="card-body">
                            {{-- ✅ رسالة النجاح عند إعادة الإرسال --}}
                            @if (session('resent'))
                                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ __('تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني') }}
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            @endif

                            {{-- ✅ الرسالة التوجيهية --}}
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ __('قبل المتابعة، يرجى التحقق من بريدك الإلكتروني للعثور على رابط التفعيل') }}
                            </div>

                            {{-- ✅ زر إعادة الإرسال --}}
                            <p class="mb-4 text-center">
                                {{ __('لم تستلم البريد الإلكتروني؟') }}
                                <br>
                                <form class="d-inline mt-2" method="POST" action="{{ route('verification.resend') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 m-0 text-decoration-none fw-bold">
                                        <i class="fas fa-redo me-1"></i> {{ __('أعد إرسال رابط التحقق') }}
                                    </button>
                                </form>
                            </p>

                            {{-- ✅ معلومات إضافية للمستخدم --}}
                            <div class="user-info mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-envelope me-2"></i>
                                    <span><strong>{{ __('البريد الإلكتروني') }}:</strong> {{ Auth::user()->email }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock me-2"></i>
                                    <span><strong>{{ __('تاريخ التسجيل') }}:</strong> {{ Auth::user()->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>

                            {{-- ✅ أزرار التنقل --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('logout') }}" class="btn btn-link text-decoration-none"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-1"></i> {{ __('تسجيل الخروج') }}
                                </a>

                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> {{ __('العودة') }}
                                </a>
                            </div>
                        </div>

                        {{-- ✅ معلومات أمان إضافية --}}
                        <div class="card-footer">
                            <div class="text-muted small">
                                <i class="fas fa-shield-alt me-1"></i>
                                {{ __('هذه الخطوة تساعد في حماية أمان حسابك') }}
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

    {{-- ✅ Form Logout Hidden --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
