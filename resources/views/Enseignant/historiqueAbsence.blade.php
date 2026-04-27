{{-- ✅ المسار: resources/views/enseignant/HistoriqueAbsence.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('تاريخ الغيابات') }} - {{ config('app.name', '4ayab') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --secondary: #0ea5e9;
            --accent: #f59e0b;
            --success: #10b981;
            --danger: #ef4444;
            --info: #3b82f6;
            --warning: #f59e0b;
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
        .history-page {
            position: relative;
            overflow: hidden;
            padding: 2rem 1rem;
            min-height: 100vh;
        }

        .history-page::before,
        .history-page::after {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 25s ease-in-out infinite;
            pointer-events: none;
        }

        .history-page::before {
            top: -200px;
            right: -200px;
            animation-delay: 0s;
        }

        .history-page::after {
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
        .history-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
            animation: fadeInDown 0.6s ease-out;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .history-title {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .history-title i {
            font-size: 1.5rem;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.95);
            color: var(--primary);
            border: none;
            border-radius: 10px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Filters Card */
        .filters-card {
            background: rgba(255, 255, 255, 0.98);
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            margin-bottom: 2rem;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .filters-card .card-body {
            padding: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control, .select2-container--default .select2-selection--single {
            border-radius: 10px;
            border: 2px solid var(--border);
            padding: 0.625rem 1rem;
            transition: all 0.2s ease;
            background: var(--bg-light);
            height: auto;
            min-height: 42px;
        }

        .form-control:focus, .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1);
            outline: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
            padding-left: 0;
            color: var(--text-dark);
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
            right: 10px;
        }

        .filter-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .btn-filter {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.4);
        }

        .btn-reset {
            background: white;
            color: var(--text-dark);
            border: 2px solid var(--border);
            border-radius: 10px;
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            border-color: var(--primary);
            background: var(--bg-light);
        }

        /* Table Card */
        .table-card {
            background: rgba(255, 255, 255, 0.98);
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            animation: slideUp 0.6s ease-out 0.1s backwards;
            overflow: hidden;
        }

        .table-card .card-body {
            padding: 1rem;
        }

        .table-responsive {
            border-radius: 16px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }

        .table thead th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: var(--border);
            font-size: 0.95rem;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        /* Badges */
        .badge {
            padding: 0.5rem 0.875rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .badge-success {
            background: linear-gradient(135deg, var(--success), #059669);
            color: white;
        }

        .badge-danger {
            background: linear-gradient(135deg, var(--danger), #dc2626);
            color: white;
        }

        .badge-info {
            background: linear-gradient(135deg, var(--info), #2563eb);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 1.1rem;
        }

        /* Pagination */
        .pagination {
            justify-content: center;
            margin-top: 1.5rem;
        }

        .page-item .page-link {
            border: none;
            color: var(--text-dark);
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .page-item:not(.active) .page-link:hover {
            background: var(--bg-light);
            color: var(--primary);
        }

        /* Stats Bar */
        .stats-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            animation: fadeInDown 0.6s ease-out 0.2s backwards;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 1rem 1.5rem;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: var(--shadow);
            min-width: 200px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .stat-icon.total { background: linear-gradient(135deg, var(--primary), var(--secondary)); }
        .stat-icon.absent { background: linear-gradient(135deg, var(--danger), #dc2626); }
        .stat-icon.present { background: linear-gradient(135deg, var(--success), #059669); }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* Footer */
        .history-footer {
            text-align: center;
            padding: 2rem 1rem;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            margin-top: 3rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .history-page { padding: 1rem; }
            .history-header { flex-direction: column; text-align: center; }
            .filter-actions { justify-content: center; }
            .stats-bar { flex-direction: column; }
            .stat-card { width: 100%; justify-content: center; }
            .table thead { display: none; }
            .table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid var(--border);
                border-radius: 12px;
                padding: 1rem;
                background: white;
            }
            .table tbody td {
                display: flex;
                justify-content: space-between;
                padding: 0.5rem 0;
                border: none;
                border-bottom: 1px dashed var(--border);
            }
            .table tbody td:last-child { border-bottom: none; }
            .table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--text-muted);
            }
        }

        /* RTL Support */
        [dir="rtl"] { text-align: right; }
        [dir="rtl"] .select2-container--default .select2-selection--single .select2-selection__arrow { left: 10px; right: auto; }
    </style>
</head>
<body>
    <div class="history-page">
        <div class="container">

            {{-- 📌 Header with Title & Back Button --}}
            <div class="history-header">
                <h1 class="history-title">
                    <i class="fas fa-history"></i>
                    {{ __('Historique des Absences') }}
                </h1>
                <a href="{{ route('list.seance') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> {{ __('Retour') }}
                </a>
            </div>

            {{-- 📊 Stats Bar --}}
            <div class="stats-bar">
                <div class="stat-card">
                    <div class="stat-icon total">
                        <i class="fas fa-list"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $absences->total() ?? 0 }}</div>
                        <div class="stat-label">{{ __('إجمالي السجلات') }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon absent">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $absences->where('etat', 1)->count() ?? 0 }}</div>
                        <div class="stat-label">{{ __('غيابات') }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon present">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $absences->where('etat', 0)->count() ?? 0 }}</div>
                        <div class="stat-label">{{ __('حضور') }}</div>
                    </div>
                </div>
            </div>

            {{-- 🔍 Filters Card --}}
            <div class="card filters-card">
                <div class="card-body">
                    <div class="row g-3">
                        {{-- Matière Filter --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('المادة') }}</label>
                            <select class="form-control select2" id="matiereFilter" data-placeholder="{{ __('كل المواد') }}">
                                <option value="">{{ __('كل المواد') }}</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}" {{ request('matiere') == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom_mat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filière Filter --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('الشعبة') }}</label>
                            <select class="form-control select2" id="filiereFilter" data-placeholder="{{ __('كل الشعب') }}">
                                <option value="">{{ __('كل الشعب') }}</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}" {{ request('filiere') == $filiere->id ? 'selected' : '' }}>
                                        {{ $filiere->nom_filiere }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date Filter --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('التاريخ') }}</label>
                            <input type="date" class="form-control" id="dateFilter" value="{{ request('date') }}">
                        </div>
                    </div>

                    {{-- Filter Actions --}}
                    <div class="filter-actions">
                        <button id="applyFilters" class="btn-filter">
                            <i class="fas fa-filter"></i> {{ __('تطبيق الفلتر') }}
                        </button>
                        <a href="{{ route('historique.absence') }}" class="btn-reset">
                            <i class="fas fa-undo"></i> {{ __('إعادة تعيين') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- 📊 Table Card --}}
            <div class="card table-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="absencesTable">
                            <thead>
                                <tr>
                                    <th>{{ __('الحالة') }}</th>
                                    <th>{{ __('الطالب(ة)') }}</th>
                                    <th>{{ __('التاريخ') }}</th>
                                    <th>{{ __('البداية') }}</th>
                                    <th>{{ __('النهاية') }}</th>
                                    <th>{{ __('النوع') }}</th>
                                    <th>{{ __('المادة') }}</th>
                                    <th>{{ __('الشعبة') }}</th>
                                    <th>{{ __('الفصل') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($absences as $absence)
                                    {{-- ✅ Data attributes for client-side filtering --}}
                                    <tr data-matiere="{{ $absence->seance->matiere->id ?? '' }}"
                                        data-filiere="{{ $absence->seance->matiere->filiere->id ?? '' }}"
                                        data-date="{{ $absence->seance->date ?? '' }}">

                                        {{-- État --}}
                                        <td data-label="{{ __('الحالة') }}">
                                            @if($absence->etat)
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times-circle"></i> {{ __('Absent(e)') }}
                                                </span>
                                            @else
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle"></i> {{ __('Présent(e)') }}
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Étudiant --}}
                                        <td data-label="{{ __('الطالب(ة)') }}">
                                            <strong>{{ $absence->etudiant->nom_etu ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $absence->etudiant->prenom_etu ?? '' }}</small>
                                        </td>

                                        {{-- Date & Times --}}
                                        <td data-label="{{ __('التاريخ') }}">{{ $absence->seance->date ?? 'N/A' }}</td>
                                        <td data-label="{{ __('البداية') }}">{{ $absence->seance->heure_debut ?? 'N/A' }}</td>
                                        <td data-label="{{ __('النهاية') }}">{{ $absence->seance->heure_fin ?? 'N/A' }}</td>

                                        {{-- Type --}}
                                        <td data-label="{{ __('النوع') }}">
                                            <span class="badge badge-info">
                                                {{ strtoupper($absence->seance->type ?? '') }}
                                            </span>
                                        </td>

                                        {{-- Matière / Filière / Semestre --}}
                                        <td data-label="{{ __('المادة') }}">{{ $absence->seance->matiere->nom_mat ?? 'N/A' }}</td>
                                        <td data-label="{{ __('الشعبة') }}">{{ $absence->seance->matiere->filiere->nom_filiere ?? 'N/A' }}</td>
                                        <td data-label="{{ __('الفصل') }}">{{ $absence->seance->matiere->semestre->nom_sem ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9">
                                            <div class="empty-state">
                                                <i class="fas fa-inbox"></i>
                                                <p>{{ __('لا توجد غيابات للعرض حسب المعايير المحددة') }}</p>
                                                <a href="{{ route('historique.absence') }}" class="btn-reset mt-2">
                                                    <i class="fas fa-undo"></i> {{ __('إعادة تعيين الفلتر') }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- 📄 Pagination --}}
                    @if($absences instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-3">
                            {{ $absences->withQueryString()->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- 🦶 Footer --}}
            <footer class="history-footer">
                <p>
                    &copy; {{ date('Y') }} <strong>{{ config('app.name', '4ayab') }}</strong>.
                    {{ __('جميع الحقوق محفوظة.') }}
                    <br>
                    <small style="opacity: 0.8;">
                        {{ __('نظام تتبع الغياب الذكي للمؤسسات التعليمية') }}
                    </small>
                </p>
            </footer>
        </div>
    </div>

    <!-- jQuery & Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- ✅ Scripts --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                placeholder: $(this).data('placeholder'),
                allowClear: true,
                language: '{{ app()->getLocale() }}'
            });
        }

        // Filter button: Server-side filtering (recommended for large datasets)
        document.getElementById('applyFilters')?.addEventListener('click', function() {
            const matiere = document.getElementById('matiereFilter').value;
            const filiere = document.getElementById('filiereFilter').value;
            const date = document.getElementById('dateFilter').value;

            const params = new URLSearchParams();
            if (matiere) params.append('matiere', matiere);
            if (filiere) params.append('filiere', filiere);
            if (date) params.append('date', date);

            window.location.href = '{{ route('historique.absence') }}?' + params.toString();
        });

        // Optional: Real-time client-side filtering (for small datasets only)
        const filters = {
            matiere: document.getElementById('matiereFilter'),
            filiere: document.getElementById('filiereFilter'),
            date: document.getElementById('dateFilter')
        };

        function applyClientFilters() {
            const matiereVal = filters.matiere?.value || '';
            const filiereVal = filters.filiere?.value || '';
            const dateVal = filters.date?.value || '';

            document.querySelectorAll('#absencesTable tbody tr').forEach(row => {
                if (!row.dataset.matiere) return; // Skip empty row

                const matchMatiere = !matiereVal || row.dataset.matiere === matiereVal;
                const matchFiliere = !filiereVal || row.dataset.filiere === filiereVal;
                const matchDate = !dateVal || row.dataset.date === dateVal;

                row.style.display = (matchMatiere && matchFiliere && matchDate) ? '' : 'none';
            });
        }

        // Attach event listeners for real-time filtering
        Object.values(filters).forEach(filter => {
            filter?.addEventListener('change', applyClientFilters);
        });
    });
    </script>
</body>
</html>
