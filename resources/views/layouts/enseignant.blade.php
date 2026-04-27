{{-- ✅ المسار: resources/views/layouts/enseignant.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- ✅ ضروري لـ AJAX --}}

    <title>{{ config('app.name', '4ayab') }} - Espace Professeur</title>

    {{-- ✅ Fonts --}}
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700&display=swap" rel="stylesheet">

    {{-- ✅ Bootstrap 4 CSS --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    {{-- ✅ Font Awesome 5 (أحدث من 4.7) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    {{-- ✅ Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('enseignant/css/style.css') }}">

    {{-- ✅ Stack for page-specific styles --}}
    @stack('styles')
</head>
<body>
    {{-- ✅ Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home.prof') }}">
                <img class="img-fluid" src="{{ asset('enseignant/images/enset2.png') }}" alt="ENSET" style="max-height: 40px;">
            </a>

            <button class="navbar-toggler" type="button"
                    data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                {{-- Left Side --}}
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item {{ request()->routeIs('home.prof') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('home.prof') }}">
                            {{ __('Accueil') }}
                            @if(request()->routeIs('home.prof')) <span class="sr-only">(current)</span> @endif
                        </a>
                    </li>
                </ul>

                {{-- Right Side --}}
                <ul class="navbar-nav">
                    {{-- Services Dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Services') }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('create.seance') }}">
                                <i class="fas fa-plus me-1"></i> {{ __('Créer une séance') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('list.seance') }}">
                                <i class="fas fa-clipboard-list me-1"></i> {{ __('Enregistrer absence') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('historique.absence') }}">
                                <i class="fas fa-history me-1"></i> {{ __('Consulter l\'historique') }}
                            </a>
                        </div>
                    </li>

                    {{-- Logout --}}
                    <li class="nav-item ml-lg-3">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-1"></i> {{ __('Déconnexion') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- ✅ Main Content (with padding-top for fixed navbar) --}}
    <main class="py-5 mt-5">
        @yield('content')
    </main>

    {{-- ✅ Footer --}}
    <footer class="footer bg-light py-4 mt-auto">
        <div class="container">
            <div class="row">
                {{-- SiteMap --}}
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="mb-3">{{ __('SiteMap') }}</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home.prof') }}" class="text-decoration-none text-muted">{{ __('Accueil') }}</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">{{ __('Contact') }}</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">{{ __('Services') }}</a></li>
                    </ul>

                    {{-- Social Icons --}}
                    <ul class="list-unstyled d-flex mt-3">
                        <li class="mr-3"><a href="#" class="text-muted"><i class="fab fa-facebook fa-lg"></i></a></li>
                        <li class="mr-3"><a href="#" class="text-muted"><i class="fab fa-twitter fa-lg"></i></a></li>
                        <li class="mr-3"><a href="#" class="text-muted"><i class="fab fa-linkedin fa-lg"></i></a></li>
                        <li><a href="#" class="text-muted"><i class="fab fa-google-plus fa-lg"></i></a></li>
                    </ul>
                </div>

                {{-- Contact Info --}}
                <div class="col-md-6">
                    <h5 class="mb-3">Lycée Moulay Rachid</h5>
                    <p class="text-muted mb-0 small">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        Lycée Moulay Rachid Agoulmuse<br>
                        <i class="fas fa-phone me-1"></i> 05 35 37 00 59 <br>
                        <i class="fas fa-fax me-1"></i>
                    </p>
                </div>
            </div>

            {{-- Copyright --}}
            <hr class="my-4">
            <div class="text-center text-muted small">
                &copy; {{ date('Y') }} <strong>{{ config('app.name', '4ayab') }}</strong>.
                {{ __('Tous droits réservés.') }}
            </div>
        </div>
    </footer>

    {{-- ✅ Scripts (ترتيب صحيح: jQuery → Popper → Bootstrap) --}}
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    {{-- ✅ Stack for page-specific scripts --}}
    @stack('scripts')
</body>
</html>
