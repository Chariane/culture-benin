<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Espace Modérateur' }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        body {
            background: #f8fafc;
            color: #111827;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid rgba(0,0,0,0.06);
            overflow-y: auto;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            padding: 20px;
            z-index: 1000;
        }

        .content-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - var(--sidebar-width));
        }

        .content {
            flex: 1;
            padding: 28px;
            overflow-y: auto;
            width: 100%;
            position: relative;
        }

        .main-container {
            max-width: 100%;
            overflow: hidden;
        }

        .content > * {
            max-width: 100%;
        }

        .sidebar a {
            display:flex;
            gap:10px;
            align-items:center;
            padding:10px 12px;
            border-radius:8px;
            color:#4b5563;
            text-decoration:none;
            margin-bottom:6px;
            transition:all .15s;
        }

        .sidebar a:hover {
            transform: translateX(6px);
            background:#f3f4f6;
            color:#111827;
        }

        .sidebar a.active {
            background:#e6f0ff;
            color:#0b5ed7;
            font-weight:600;
            border-left:4px solid #0b5ed7;
        }

        .section-title {
            font-size:11px;
            text-transform:uppercase;
            margin-top:18px;
            margin-bottom:8px;
            color:#6b7280;
            font-weight:600;
        }

        .profile-img {
            width:110px;
            height:110px;
            object-fit:cover;
            border-radius:9999px;
        }

        nav.navbar {
            background: #fff;
            border-radius: 8px;
            border: 1px solid rgba(0,0,0,.04);
            flex-shrink: 0;
            margin: 0 28px 28px 0;
        }

        /* Empêche le débordement des tableaux */
        .table-responsive-wrapper {
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Ajustements pour les modals */
        .modal-backdrop {
            z-index: 1050;
        }
        .modal {
            z-index: 1060;
        }

        /* RESPONSIVE sidebar */
        @media(max-width: 768px){
            body {
                flex-direction: column;
            }
            
            .sidebar {
                transform: translateX(-100%);
                transition: .3s;
                z-index: 9999;
                height: 100vh;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .content-wrapper {
                margin-left: 0 !important;
                width: 100%;
            }
            .content {
                padding: 15px;
            }
            nav.navbar {
                margin: 0 15px 15px 0;
            }
        }

        /* Dark mode */
        body.bg-dark {
            background: #1a202c !important;
        }
        body.bg-dark .sidebar {
            background: #2d3748;
            border-right-color: #4a5568;
        }
        body.bg-dark .sidebar a {
            color: #cbd5e0;
        }
        body.bg-dark .sidebar a:hover {
            background: #4a5568;
            color: #fff;
        }
        body.bg-dark .sidebar a.active {
            background: #3182ce;
            color: white;
        }
        body.bg-dark nav.navbar {
            background: #2d3748;
            border-color: #4a5568;
        }
        body.bg-dark .card {
            background: #2d3748;
            border-color: #4a5568;
        }
        body.bg-dark .table {
            color: #e2e8f0;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</head>
<body>

    <div class="sidebar" id="sidebar">
        <h5 class="text-center mb-3">MODÉRATEUR — CULTURE BÉNIN</h5>

        <div class="text-center mb-3">
            @php
                $user = auth()->user();
                $defaultPhoto = ($user->genre === 'Femme')
                                ? asset('female.jpg')
                                : asset('male.jpg');
                $photoUrl = $user->photo ? asset('storage/' . $user->photo) : $defaultPhoto;
            @endphp

            <img src="{{ $photoUrl }}" alt="Photo" class="profile-img mx-auto d-block mb-2 shadow-sm">
            <div class="fw-bold text-center">{{ $user->nom }}</div>
            <div class="text-muted small text-center">Modérateur</div>
        </div>

        <a href="{{ route('moderateur.dashboard') }}" class="{{ request()->routeIs('moderateur.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Tableau de bord
        </a>

        <div class="section-title">Gestion</div>

        <a href="#" onclick="document.getElementById('logoutForm').submit(); return false;">
            <i class="bi bi-box-arrow-right"></i> Déconnexion
        </a>

        <form id="logoutForm" action="{{ route('logout') }}" method="POST">@csrf</form>
    </div>

    <div class="content-wrapper">
        <nav class="navbar px-3 py-2 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button id="toggleSidebar" class="btn btn-outline-secondary btn-sm d-md-none">
                    <i class="bi bi-list"></i>
                </button>

                <button id="toggleTheme" class="btn btn-outline-secondary btn-sm">
                    <i id="themeIcon" class="bi bi-moon"></i>
                </button>

                <h5 class="m-0">{{ $title ?? '' }}</h5>
            </div>

            <div>
                <span class="me-3">{{ auth()->user()->nom }}</span>
                <a href="#" class="btn btn-outline-danger btn-sm"
                   onclick="document.getElementById('logoutForm').submit();return false;">
                   Déconnexion
                </a>
            </div>
        </nav>

        <div class="content">
            <div class="main-container">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    (function(){
        const icon = document.getElementById('themeIcon');
        const saved = localStorage.getItem('moderateur_theme') || 'light';

        if(saved === 'dark'){
            document.body.classList.add('bg-dark','text-white');
            icon.className='bi bi-sun';
        }

        document.getElementById('toggleTheme').addEventListener('click', function(){
            const dark = document.body.classList.toggle('bg-dark');
            document.body.classList.toggle('text-white');
            icon.className = dark ? 'bi bi-sun' : 'bi bi-moon';
            localStorage.setItem('moderateur_theme', dark ? 'dark' : 'light');
        });
    })();

    document.getElementById('toggleSidebar').addEventListener('click', function(){
        document.getElementById('sidebar').classList.toggle('active');
    });
</script>

@stack('scripts')
</body>
</html>