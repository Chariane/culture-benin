<!DOCTYPE html>
<html lang="fr" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="CultureHub - La plateforme de référence pour la culture béninoise. Découvrez le riche patrimoine culturel du Bénin à travers des contenus authentiques.">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'CultureHub - Plateforme Culturelle Béninoise')">
    <meta property="og:description" content="Découvrez et célébrez la culture béninoise sous toutes ses formes. Notre mission : préserver et promouvoir le patrimoine culturel du Bénin.">
    <meta property="og:image" content="{{ asset('images/og-beni n.jpg') }}">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'CultureHub - Plateforme Culturelle Béninoise')">
    <meta property="twitter:description" content="Découvrez et célébrez la culture béninoise sous toutes ses formes.">
    <meta property="twitter:image" content="{{ asset('images/twitter-benin.jpg') }}">
    
    <title>@yield('title', 'CultureHub - La Culture Béninoise à Portée de Clic')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon-benin.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon-benin.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&family=Cinzel:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        benin: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        beninYellow: {
                            50: '#fefce8',
                            100: '#fef9c3',
                            200: '#fef08a',
                            300: '#fde047',
                            400: '#facc15',
                            500: '#eab308',
                            600: '#ca8a04',
                            700: '#a16207',
                            800: '#854d0e',
                            900: '#713f12',
                        },
                        beninRed: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        }
                    },
                    fontFamily: {
                        'display': ['Cinzel', 'Playfair Display', 'serif'],
                        'body': ['Inter', 'system-ui', 'sans-serif'],
                        'cinzel': ['Cinzel', 'serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @yield('styles')
    @stack('head-scripts')
</head>
<body class="font-body bg-white text-gray-900 min-h-screen flex flex-col transition-colors duration-200 dark:bg-gray-900 dark:text-gray-100">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo à l'extrême gauche -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 mr-8">
                        <div class="w-10 h-10 rounded-full gradient-benin-vertical flex items-center justify-center">
                            <i class="fas fa-landmark text-white text-sm"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-cinzel font-bold text-gray-900 dark:text-white">CultureBénin</h1>
                        </div>
                    </a>
                    
                    <!-- Types de contenu dans la navbar principale -->
                    @if(isset($typesContenus) && count($typesContenus) > 0)
                    <div class="hidden lg:flex items-center space-x-1">
                        @foreach($typesContenus as $type)
                        <a href="{{ route('contenus.by-type', $type->id_type_contenu) }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('contenus.by-type', $type->id_type_contenu) ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            {{ $type->nom_contenu }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Navigation Desktop -->
                <div class="hidden lg:flex items-center space-x-4">
                    <a href="{{ route('contenus.index') }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('contenus.index') ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-compass mr-2"></i>Explorer
                    </a>
                    
                    <a href="{{ route('regions.index') }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('regions.index') ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-map-marked-alt mr-2"></i>Régions
                    </a>
                    
                    <a href="{{ route('auteurs.index') }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('auteurs.index') ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-users mr-2"></i>Auteurs
                    </a>
                    
                    <!-- Bouton mode clair/sombre -->
                    <button onclick="toggleDarkMode()" class="ml-4 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i id="dark-mode-icon" class="fas fa-moon text-gray-600 dark:text-gray-300"></i>
                    </button>
                    
                    @auth
                    <!-- Menu utilisateur -->
                    <div x-data="{ open: false }" class="relative ml-2">
                        <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            @php
                                $user = auth()->user();
                                $photoUrl = null;
                                
                                if ($user->photo && file_exists(public_path('storage/photos/' . basename($user->photo)))) {
                                    $photoUrl = asset('storage/photos/' . basename($user->photo));
                                } else {
                                    if ($user->sexe === 'Homme') {
                                        $photoUrl = asset('male.jpg');
                                    } elseif ($user->sexe === 'Femme') {
                                        $photoUrl = asset('female.jpg');
                                    } else {
                                        $photoUrl = asset('images/default-avatar.png');
                                    }
                                }
                            @endphp
                            
                            @if($photoUrl)
                            <img src="{{ $photoUrl }}" 
                                 alt="{{ $user->nom }}"
                                 class="w-8 h-8 rounded-full border border-gray-300 dark:border-gray-600">
                            @else
                            <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 font-semibold">
                                {{ strtoupper(substr($user->nom, 0, 1)) }}
                            </div>
                            @endif
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                            <a href="{{ route('profil.show') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-user-circle mr-2"></i>Mon profil
                            </a>
                            <a href="{{ route('favoris.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-heart mr-2"></i>Favoris
                            </a>
                            
                            @if(in_array($user->role->nom ?? '', ['Administrateur', 'Modérateur', 'Auteur']))
                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                            
                            @if($user->role->nom === 'Administrateur')
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-shield-alt mr-2"></i>Administration
                            </a>
                            @endif
                            
                            @if($user->role->nom === 'Auteur')
                            <a href="{{ route('auteur.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-pen-fancy mr-2"></i>Espace Auteur
                            </a>
                            @endif
                            
                            @if($user->role->nom === 'Modérateur')
                            <a href="{{ route('moderateur.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-pen-fancy mr-2"></i>Espace Modérateur
                            </a>
                            @endif
                            @endif
                            
                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <!-- Boutons authentification -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-benin-500 text-white hover:bg-benin-600">
                            Inscription
                        </a>
                    </div>
                    @endauth
                </div>

                <!-- Menu mobile -->
                <div class="lg:hidden">
                    <button id="mobile-menu-button" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-bars text-gray-600 dark:text-gray-300"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Menu Mobile -->
    <div id="mobile-menu" class="lg:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 hidden">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('contenus.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                <i class="fas fa-compass mr-3"></i>Explorer
            </a>
            
            <a href="{{ route('regions.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                <i class="fas fa-map-marked-alt mr-3"></i>Régions
            </a>
            
            <a href="{{ route('auteurs.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                <i class="fas fa-users mr-3"></i>Auteurs
            </a>
            
            <!-- Types de contenu mobile -->
            @if(isset($typesContenus) && count($typesContenus) > 0)
            <div class="border-t border-gray-200 dark:border-gray-700 pt-2 mt-2">
                <h3 class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Types de contenus
                </h3>
                @foreach($typesContenus as $type)
                <a href="{{ route('contenus.by-type', $type->id_type_contenu) }}" 
                   class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                    {{ $type->nom_contenu }}s
                </a>
                @endforeach
            </div>
            @endif
            
            <!-- Authentification mobile -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-2 mt-2">
                @auth
                @php
                    $user = auth()->user();
                    $photoUrl = null;
                    
                    if ($user->photo && file_exists(public_path('storage/photos/' . basename($user->photo)))) {
                                        $photoUrl = asset('storage/photos/' . basename($user->photo));
                                    } else {
                                        if ($user->sexe === 'M') {
                                            $photoUrl = asset('male.jpg');
                                        } elseif ($user->sexe === 'F') {
                                            $photoUrl = asset('female.jpg');
                                        } else {
                                            $photoUrl = asset('images/default-avatar.png');
                                        }
                                    }
                                @endphp
                                
                                <div class="flex items-center px-3 py-3 mb-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    @if($photoUrl)
                                    <img src="{{ $photoUrl }}" 
                                         alt="{{ $user->nom }}"
                                         class="w-10 h-10 rounded-full border border-gray-300 dark:border-gray-600">
                                    @else
                                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 font-semibold">
                                        {{ strtoupper(substr($user->nom, 0, 1)) }}
                                    </div>
                                    @endif
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->nom }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->role->nom ?? 'Membre' }}</div>
                                    </div>
                                </div>
                                
                                <a href="{{ route('profil.show') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                                    <i class="fas fa-user-circle mr-3"></i>Mon profil
                                </a>
                                
                                <a href="{{ route('favoris.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                                    <i class="fas fa-heart mr-3"></i>Favoris
                                </a>
                                
                                @if(in_array($user->role->nom ?? '', ['Administrateur', 'Modérateur', 'Auteur']))
                                @if($user->role->nom === 'Administrateur')
                                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                                    <i class="fas fa-shield-alt mr-3"></i>Administration
                                </a>
                                @endif
                                
                                @if($user->role->nom === 'Auteur')
                                <a href="{{ route('auteur.dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                                    <i class="fas fa-pen-fancy mr-3"></i>Espace Auteur
                                </a>
                                @endif
                                
                                @if($user->role->nom === 'Modérateur')
                                <a href="{{ route('moderateur.dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                                    <i class="fas fa-pen-fancy mr-3"></i>Espace Modérateur
                                </a>
                                @endif
                                @endif
                                
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-3 py-2 rounded-lg text-base font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        <i class="fas fa-sign-out-alt mr-3"></i>Déconnexion
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                                    <i class="fas fa-sign-in-alt mr-3"></i>Connexion
                                </a>
                                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg text-base font-medium bg-benin-500 text-white hover:bg-benin-600">
                                    <i class="fas fa-user-plus mr-3"></i>Inscription
                                </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Messages flash -->
                @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Contenu principal -->
                <main class="flex-grow">
                    @yield('content')
                </main>

                <!-- Footer -->
                <footer class="bg-gray-800 text-white mt-16">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                            <!-- Colonne 1: Présentation -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded-full gradient-benin-vertical flex items-center justify-center">
                                        <i class="fas fa-landmark text-white"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-cinzel font-bold">CultureBénin</h2>
                                        <p class="text-xs text-gray-300">Depuis 2024</p>
                                    </div>
                                </div>
                                <p class="text-gray-300 text-sm">
                                    Plateforme officielle de promotion du patrimoine culturel béninois.
                                </p>
                                <div class="flex space-x-3">
                                    <a href="#" class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center hover:bg-benin-500 transition">
                                        <i class="fab fa-facebook-f text-sm"></i>
                                    </a>
                                    <a href="#" class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center hover:bg-beninYellow-500 transition">
                                        <i class="fab fa-twitter text-sm"></i>
                                    </a>
                                    <a href="#" class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center hover:bg-beninRed-500 transition">
                                        <i class="fab fa-instagram text-sm"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Colonne 2: Explorer -->
                            <div>
                                <h3 class="text-lg font-cinzel font-semibold mb-4">Explorer</h3>
                                <ul class="space-y-2">
                                    <li><a href="{{ route('contenus.index') }}" class="text-gray-300 hover:text-white transition">Tous les contenus</a></li>
                                    <li><a href="{{ route('auteurs.index') }}" class="text-gray-300 hover:text-white transition">Nos auteurs</a></li>
                                    <li><a href="{{ route('regions.index') }}" class="text-gray-300 hover:text-white transition">Régions du Bénin</a></li>
                                </ul>
                            </div>

                            <!-- Colonne 3: Catégories -->
                            <div>
                                <h3 class="text-lg font-cinzel font-semibold mb-4">Catégories</h3>
                                <ul class="space-y-2">
                                    @if(isset($typesContenus) && count($typesContenus) > 0)
                                        @foreach($typesContenus as $type)
                                        <li><a href="{{ route('contenus.by-type', $type->id_type_contenu) }}" class="text-gray-300 hover:text-white transition">{{ $type->nom_contenu }}s</a></li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>

                            <!-- Colonne 4: Contact -->
                            <div>
                                <h3 class="text-lg font-cinzel font-semibold mb-4">Contact</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center text-gray-300 text-sm">
                                        <i class="fas fa-envelope mr-2"></i>
                                        <span>contact@culturebenin.bj</span>
                                    </div>
                                    <div class="flex items-center text-gray-300 text-sm">
                                        <i class="fas fa-phone mr-2"></i>
                                        <span>+229 XX XX XX XX</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                            <p class="text-gray-400 text-sm">
                                © {{ date('Y') }} CultureBénin. Tous droits réservés.
                            </p>
                        </div>
                    </div>
                </footer>

                <!-- Scripts -->
                <script>
                    // Dark mode avec localStorage
                    function toggleDarkMode() {
                        const html = document.documentElement;
                        const icon = document.getElementById('dark-mode-icon');
                        
                        if (html.classList.contains('dark')) {
                            html.classList.remove('dark');
                            localStorage.setItem('darkMode', 'false');
                            icon.className = 'fas fa-moon text-gray-600 dark:text-gray-300';
                        } else {
                            html.classList.add('dark');
                            localStorage.setItem('darkMode', 'true');
                            icon.className = 'fas fa-sun text-gray-600 dark:text-gray-300';
                        }
                    }

                    // Vérifier la préférence au chargement
                    document.addEventListener('DOMContentLoaded', function() {
                        // Dark mode
                        if (localStorage.getItem('darkMode') === 'true' || 
                            (localStorage.getItem('darkMode') === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                            document.documentElement.classList.add('dark');
                            document.getElementById('dark-mode-icon').className = 'fas fa-sun text-gray-600 dark:text-gray-300';
                        }

                        // Menu mobile
                        const mobileMenuButton = document.getElementById('mobile-menu-button');
                        const mobileMenu = document.getElementById('mobile-menu');
                        
                        if (mobileMenuButton && mobileMenu) {
                            mobileMenuButton.addEventListener('click', function() {
                                mobileMenu.classList.toggle('hidden');
                            });
                        }
                        
                        // Fermer le menu mobile en cliquant à l'extérieur
                        document.addEventListener('click', function(event) {
                            if (mobileMenuButton && mobileMenu && 
                                !mobileMenuButton.contains(event.target) && 
                                !mobileMenu.contains(event.target)) {
                                mobileMenu.classList.add('hidden');
                            }
                        });
                    });
                </script>

                @yield('scripts')
                @stack('scripts')
            </body>
            </html>