{{-- ✅ المسار: resources/views/enseignant/EspaceProf.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('فضاء الأستاذ') }} - {{ config('app.name', '4ayab') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --secondary: #0ea5e9;
            --accent: #f59e0b;
            --success: #10b981;
            --info: #3b82f6;
            --bg-light: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-hover: 0 20px 25px -5px rgb(0 0 0 / 0.15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 50%, #bfdbfe 100%);
            font-family: {{ app()->getLocale() === 'ar' ? "'Cairo', sans-serif" : "'Nunito', sans-serif" }};
            min-height: 100vh;
            line-height: 1.6;
            color: var(--text-dark);
        }

        /* تأثير دوائر متحركة في الخلفية */
        .espace-page {
            position: relative;
            overflow: hidden;
            padding: 3rem 1rem;
            min-height: 100vh;
        }

        .espace-page::before,
        .espace-page::after {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 25s ease-in-out infinite;
            pointer-events: none;
        }

        .espace-page::before {
            top: -200px;
            right: -200px;
            animation-delay: 0s;
        }

        .espace-page::after {
            bottom: -300px;
            left: -100px;
            animation-delay: -12s;
            width: 400px;
            height: 400px;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.95); }
        }

        /* Header Section */
        .espace-header {
            text-align: center;
            margin-bottom: 4rem;
            animation: fadeInDown 0.8s ease-out;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .espace-title {
            font-size: clamp(1.75rem, 4vw, 2.5rem);
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }

        .espace-title span {
            background: linear-gradient(135deg, #fff, #bfdbfe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .espace-subtitle {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.95);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* Service Cards */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .service-card {
            background: rgba(255, 255, 255, 0.98);
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeInUp 0.6s ease-out backwards;
            position: relative;
            z-index: 1;
        }

        .service-card:nth-child(1) { animation-delay: 0.1s; }
        .service-card:nth-child(2) { animation-delay: 0.2s; }
        .service-card:nth-child(3) { animation-delay: 0.3s; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-hover);
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .service-card:hover::before {
            opacity: 1;
        }

        .service-card .card-body {
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .service-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            transition: transform 0.3s ease;
        }

        .service-card:hover .service-icon {
            transform: rotate(5deg) scale(1.1);
        }

        .service-icon.primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.4);
        }

        .service-icon.success {
            background: linear-gradient(135deg, var(--success), #059669);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        .service-icon.info {
            background: linear-gradient(135deg, var(--info), #2563eb);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }

        .service-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .service-desc {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .service-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            font-size: 0.9rem;
        }

        .service-btn.primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
        }

        .service-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.4);
        }

        .service-btn.success {
            background: linear-gradient(135deg, var(--success), #059669);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .service-btn.success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        .service-btn.info {
            background: linear-gradient(135deg, var(--info), #2563eb);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .service-btn.info:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }

        /* User Info Bar */
        .user-bar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto 3rem;
            box-shadow: var(--shadow);
            animation: fadeInDown 0.8s ease-out;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-dark);
        }

        .user-role {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.25rem;
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .logout-btn:hover {
            background: var(--primary);
            color: white;
        }

        /* Footer */
        .espace-footer {
            text-align: center;
            padding: 2rem 1rem;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            margin-top: 4rem;
        }

        .espace-footer a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }

        .espace-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .espace-page {
                padding: 2rem 1rem;
            }
            .user-bar {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            .services-grid {
                grid-template-columns: 1fr;
            }
            .service-card .card-body {
                padding: 2rem 1.5rem;
            }
            .service-icon {
                width: 70px;
                height: 70px;
                font-size: 1.75rem;
            }
        }

        /* RTL Support */
        [dir="rtl"] {
            text-align: right;
        }
        [dir="rtl"] .service-card .card-body {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="espace-page">
        <div class="container">

            {{-- 👤 User Info Bar --}}
            <div class="user-bar">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <div class="user-name">{{ Auth::user()->name ?? __('أستاذ') }}</div>
                        <div class="user-role">{{ __('فضاء الأساتذة') }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> {{ __('تسجيل الخروج') }}
                    </button>
                </form>
            </div>

            {{-- 🎓 قسم الترحيب --}}
            <div class="espace-header">
                <h1 class="espace-title">
                    {{ __('Espace') }} <span>{{ __('Professeurs') }}</span>
                </h1>
                <p class="espace-subtitle">
                    {{ __('مرحباً بك في فضاء الأستاذ. استخدم تطبيق') }}
                    <strong>{{ __('Gestion des absences') }}</strong>
                    {{ __('لإنشاء الحصص، تسجيل الغيابات، واستشارة تاريخ الطلاب.') }}
                </p>
            </div>

            {{-- 🛠️ قسم الخدمات --}}
            <div class="services-grid">
                {{-- 1. Créer une séance --}}
                <div class="service-card">
                    <div class="card-body">
                        <div class="service-icon primary">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <h4 class="service-title">{{ __('Créer une séance') }}</h4>
                        <p class="service-desc">
                            {{ __('أنشئ حصة دراسية جديدة لتتمكن من تسجيل غيابات الطلاب فيها.') }}
                        </p>
                        <a href="{{ route('create.seance') }}" class="service-btn primary">
                            <i class="fas fa-plus"></i> {{ __('Commencer') }}
                        </a>
                    </div>
                </div>

                {{-- 2. Enregistrer les absences --}}
                <div class="service-card">
                    <div class="card-body">
                        <div class="service-icon success">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h4 class="service-title">{{ __('Enregistrer les absences') }}</h4>
                        <p class="service-desc">
                            {{ __('سجل غيابات الطلاب للحصص التي أنشأتها بسرعة وسهولة.') }}
                        </p>
                        <a href="{{ route('list.seance') }}" class="service-btn success">
                            <i class="fas fa-list"></i> {{ __('Voir la liste') }}
                        </a>
                    </div>
                </div>

                {{-- 3. Consulter l'historique --}}
                <div class="service-card">
                    <div class="card-body">
                        <div class="service-icon info">
                            <i class="fas fa-history"></i>
                        </div>
                        <h4 class="service-title">{{ __('Consulter l\'historique') }}</h4>
                        <p class="service-desc">
                            {{ __('استشر تاريخ غيابات الطلاب في موادك مع إحصائيات مفصلة.') }}
                        </p>
                        <a href="{{ route('historique.absence') }}" class="service-btn info">
                            <i class="fas fa-chart-line"></i> {{ __('Consulter') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- 🦶 Footer --}}
            <footer class="espace-footer">
                <p>
                    &copy; {{ date('Y') }} <strong> ثانوية مولاي رشيد </strong>.
                    {{ __('جميع الحقوق محفوظة.') }}
                    <br>
                    <small style="opacity: 0.8;">
                        {{ __('ُ لخدمة التعليم في المغرب.') }}
                    </small>
                </p>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ✅ تأثيرات بسيطة عند التمرير
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.service-card');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>
