{{-- ✅ المسار: resources/views/enseignant/CreateS.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Créer une Séance') }} - {{ config('app.name', '4ayab') }}</title>

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
            --bg-light: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
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
        .create-page {
            position: relative;
            overflow: hidden;
            padding: 2rem 1rem;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .create-page::before,
        .create-page::after {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
        }

        .create-page::before {
            top: -200px;
            right: -200px;
            animation-delay: 0s;
        }

        .create-page::after {
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
        .create-card {
            background: rgba(255, 255, 255, 0.98);
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 700px;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* شريط علوي ملون للكارد */
        .create-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--accent));
        }

        .create-card .card-header {
            background: transparent;
            border: none;
            padding: 2rem 2rem 1rem;
            text-align: center;
        }

        .create-card .card-body {
            padding: 1rem 2rem 2rem;
        }

        /* عنوان مع شعار */
        .create-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .create-brand-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.4);
        }

        .create-brand-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .create-brand-subtitle {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* تحسين الحقول */
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid var(--border);
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
            background: var(--bg-light);
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-group label .required {
            color: #ef4444;
            margin-left: 2px;
        }

        /* تحسين الأزرار */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.4);
        }

        .btn-secondary {
            background: white;
            color: var(--text-dark);
            border: 2px solid var(--border);
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            border-color: var(--primary);
            background: var(--bg-light);
            transform: translateY(-2px);
        }

        /* تحسين رسائل الخطأ */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: var(--shadow);
            font-size: 0.9rem;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .text-danger {
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: block;
        }

        /* تحسين الـ Select */
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            padding-right: 2.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .create-card {
                border-radius: 16px;
                margin: 1rem;
            }
            .create-brand-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
            .create-brand-title {
                font-size: 1.25rem;
            }
            .btn-group {
                flex-direction: column !important;
            }
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* RTL Support */
        [dir="rtl"] {
            text-align: right;
        }
        [dir="rtl"] .form-control {
            background-position: left 0.75rem center;
            padding-right: 1rem;
            padding-left: 2.5rem;
        }
    </style>
</head>
<body>
    <div class="create-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card create-card">
                        <div class="card-header">
                            {{-- 🎓 شعار واسم المشروع --}}
                            <div class="create-brand">
                                <div class="create-brand-icon">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <h2 class="create-brand-title">(ثانوية مولاي رشيد)</h2>
                                <p class="create-brand-subtitle">{{ __('إنشاء حصة دراسية جديدة') }}</p>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('save.seance') }}" method="POST">
                                @csrf

                                {{-- ✅ عرض الأخطاء العامة --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show mb-4">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        @foreach ($errors->all() as $error)
                                            <p class="mb-0">{{ $error }}</p>
                                        @endforeach
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show mb-4">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    </div>
                                @endif

                                {{-- Matière --}}
                                <div class="form-group mb-3">
                                    <label for="matiere">
                                        {{ __('المادة') }} <span class="required">*</span>
                                    </label>
                                    <select name="matiere" id="matiere" class="form-control" required>
                                        <option value="">-- {{ __('اختر مادة') }} --</option>
                                        @isset($matieres)
                                            @foreach ($matieres as $matiere)
                                                <option value="{{ $matiere->id }}" {{ old('matiere') == $matiere->id ? 'selected' : '' }}>
                                                    {{ $matiere->nom_mat }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('matiere')
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Type --}}
                                <div class="form-group mb-3">
                                    <label for="type_seance">
                                        {{ __('نوع الحصة') }} <span class="required">*</span>
                                    </label>
                                    <select name="type_seance" id="type_seance" class="form-control" required>
                                        <option value="">-- {{ __('اختر النوع') }} --</option>
                                        <option value="cour" {{ old('type_seance') == 'cour' ? 'selected' : '' }}>📚 {{ __('دروس') }}</option>
                                        <option value="TD" {{ old('type_seance') == 'TD' ? 'selected' : '' }}>📝 {{ __('أعمال موجهة') }}</option>
                                        <option value="TP" {{ old('type_seance') == 'TP' ? 'selected' : '' }}>🔬 {{ __('أعمال تطبيقية') }}</option>
                                    </select>
                                    @error('type_seance')
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Date --}}
                                <div class="form-group mb-3">
                                    <label for="date">
                                        {{ __('التاريخ') }} <span class="required">*</span>
                                    </label>
                                    <input type="date" name="date" id="date" class="form-control"
                                           value="{{ old('date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                    @error('date')
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Heure Début / Fin --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="H_debut">
                                                {{ __('بداية الحصة') }} <span class="required">*</span>
                                            </label>
                                            <input type="time" name="H_debut" id="H_debut" class="form-control"
                                                   value="{{ old('H_debut') }}" required>
                                            @error('H_debut')
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="H_fin">
                                                {{ __('نهاية الحصة') }} <span class="required">*</span>
                                            </label>
                                            <input type="time" name="H_fin" id="H_fin" class="form-control"
                                                   value="{{ old('H_fin') }}" required>
                                            @error('H_fin')
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Hidden Fields --}}
                                <input type="hidden" name="active" value="1">
                                <input type="hidden" name="id_prof" value="{{ $id_prof ?? auth()->id() }}">

                                {{-- Submit Buttons --}}
                                <div class="form-group row mb-0 mt-4">
                                    <div class="col-12 d-flex gap-2 justify-content-center btn-group">
                                        <button type="submit" class="btn btn-primary flex-grow-1">
                                            <i class="fas fa-save"></i> {{ __('حفظ الحصة') }}
                                        </button>
                                        <a href="{{ route('seances.index') }}" class="btn btn-secondary flex-grow-1">
                                            <i class="fas fa-times"></i> {{ __('إلغاء') }}
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- ✅ معلومات مساعدة --}}
                        <div class="card-footer bg-transparent border-0 text-center pb-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                {{ __('جميع الحقول المطلوبة مشار إليها بـ *') }}
                            </small>
                        </div>
                    </div>

                    {{-- حقوق النشر --}}
                    <div class="text-center mt-4" style="color: rgba(255,255,255,0.9); font-size: 0.85rem;">
                        &copy; {{ date('Y') }} {{ config('app.name', '4ayab') }}. {{ __('جميع الحقوق محفوظة.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ✅ منع اختيار وقت نهاية قبل وقت البداية
        document.addEventListener('DOMContentLoaded', function() {
            const debut = document.getElementById('H_debut');
            const fin = document.getElementById('H_fin');

            debut?.addEventListener('change', function() {
                if (fin) fin.min = this.value;
            });

            fin?.addEventListener('change', function() {
                if (debut) debut.max = this.value;
            });
        });
    </script>
</body>
</html>
