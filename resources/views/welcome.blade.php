{{-- ✅ المسار: resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- ✅ ضروري لـ AJAX --}}
    <title>{{ config('app.name', '4ayab') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome for professional icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Styles -->
    <style>
        :root {
            --primary: #4f46e5;
            --bg: #f8fafc;
            --text: #1e293b;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Nunito', sans-serif;
            height: 100vh;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }

        /* 🎓 Professional Educational Icon */
        .brand-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 40px rgba(79, 70, 229, 0.3);
            animation: float 3s ease-in-out infinite;
        }

        .brand-icon i {
            font-size: 2.5rem;
            color: white;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .subtitle {
            font-size: 1.25rem;
            color: #64748b;
            margin-bottom: 2rem;
        }
        .links {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        .links a {
            padding: 0.75rem 1.5rem;
            background: white;
            color: var(--primary);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }
        .links a:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
        }
        .auth-links {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            display: flex;
            gap: 0.75rem;
        }
        .btn {
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .btn-outline {
            border: 1px solid var(--primary);
            color: var(--primary);
        }
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        @media (max-width: 640px) {
            .title { font-size: 2rem; }
            .auth-links { position: static; margin-bottom: 1rem; }
            .brand-icon { width: 60px; height: 60px; }
            .brand-icon i { font-size: 1.8rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- 🔐 روابط المصادقة --}}
        @if (Route::has('login'))
            <div class="auth-links">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-outline">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                    @endif
                @endauth
            </div>
        @endif

        {{-- 🎯 المحتوى الرئيسي --}}
        <div>
            {{-- 🎓 الأيقونة الاحترافية التعليمية --}}
            <div class="brand-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>

            <h1 class="title"> (ثانوية مولاي رشيد التأهيلية ـ أجلموس) </h1>
            <p class="subtitle">نظام تتبع الغياب الذكي للمؤسسات التعليمية</p>

            {{-- <div class="links">
                <a href="">Documentation</a>
                <a href="">Laracasts</a>
                <a href="">News</a>
                <a href="">GitHub</a>
            </div> --}}
        </div>
    </div>
</body>
</html>
