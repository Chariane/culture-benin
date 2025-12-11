<div class="lg:col-span-1 mb-8 lg:mb-0">
    <div class="bg-white rounded-xl shadow-soft p-6 sticky top-24">
        <!-- Photo de profil -->
        <div class="text-center mb-6">
            <div class="relative inline-block">
                <img src="{{ $photoUrl }}" 
                     alt="{{ auth()->user()->prenom }}"
                     class="w-32 h-32 rounded-full mx-auto border-4 border-white shadow-lg object-cover"
                     id="profile-photo">
                
                <!-- Formulaire d'upload de photo -->
                <form id="photo-upload-form" action="{{ route('profil.photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" id="photo-input" name="photo" accept="image/*" onchange="uploadPhoto()">
                </form>
                
                <button type="button" 
                        onclick="document.getElementById('photo-input').click()"
                        class="absolute bottom-2 right-2 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mt-4">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</h2>
            <p class="text-gray-600">{{ auth()->user()->email }}</p>
            <div class="mt-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-benin-100 text-benin-800">
                    {{ auth()->user()->role->nom_role ?? 'Lecteur' }}
                </span>
            </div>
        </div>

        <!-- Navigation du profil -->
        <nav class="space-y-1">
            <a href="{{ route('profil.show') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:text-benin-600 hover:bg-gray-50 rounded-lg font-medium {{ request()->routeIs('profil.show') ? 'text-benin-600 bg-benin-50' : '' }}">
                <i class="fas fa-user-circle w-5 h-5 mr-3"></i>
                Informations personnelles
            </a>
            <a href="{{ route('profil.security') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:text-benin-600 hover:bg-gray-50 rounded-lg font-medium {{ request()->routeIs('profil.security') ? 'text-benin-600 bg-benin-50' : '' }}">
                <i class="fas fa-shield-alt w-5 h-5 mr-3"></i>
                Sécurité
            </a>
            <a href="{{ route('profil.notifications') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:text-benin-600 hover:bg-gray-50 rounded-lg font-medium {{ request()->routeIs('profil.notifications') ? 'text-benin-600 bg-benin-50' : '' }}">
                <i class="fas fa-bell w-5 h-5 mr-3"></i>
                Notifications
            </a>
            <a href="{{ route('profil.activity') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:text-benin-600 hover:bg-gray-50 rounded-lg font-medium {{ request()->routeIs('profil.activity') ? 'text-benin-600 bg-benin-50' : '' }}">
                <i class="fas fa-history w-5 h-5 mr-3"></i>
                Activité
            </a>
            <a href="{{ route('favoris.index') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:text-benin-600 hover:bg-gray-50 rounded-lg font-medium">
                <i class="fas fa-heart w-5 h-5 mr-3"></i>
                Favoris
            </a>
            <a href="{{ route('bibliotheque.index') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:text-benin-600 hover:bg-gray-50 rounded-lg font-medium">
                <i class="fas fa-book w-5 h-5 mr-3"></i>
                Bibliothèque
            </a>
        </nav>
    </div>
</div>