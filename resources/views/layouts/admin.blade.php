@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100">
    
    <div x-data="{ sidebarOpen: false, openMenu: null }" class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Mobile Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-900 bg-opacity-75 lg:hidden"
             style="display: none;">
        </div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-amber-800 via-amber-900 to-amber-950 shadow-2xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto flex flex-col">
            
            <!-- Header Sidebar -->
            <div class="flex items-center justify-between h-20 px-6 bg-amber-950 bg-opacity-50 border-b border-amber-700 flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center shadow-lg">
                        <i class="fas fa-car text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-amber-50 font-bold text-lg tracking-wide">{{ $isFrench ? 'Auto-École' : 'Driving School' }}</h2>
                        <p class="text-amber-300 text-xs font-medium">{{ $isFrench ? 'Panneau Admin' : 'Admin Panel' }}</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-amber-200 hover:text-white transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-4 py-6 overflow-y-auto space-y-2 scrollbar-thin scrollbar-thumb-amber-700 scrollbar-track-amber-900">
                
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-4 py-3 text-amber-100 hover:bg-amber-700 hover:bg-opacity-50 rounded-xl transition-all duration-200 group">
                    <i class="fas fa-tachometer-alt text-amber-400 group-hover:text-amber-300 text-lg w-6"></i>
                    <span class="ml-3 font-medium">{{ $isFrench ? 'Tableau de bord' : 'Dashboard' }}</span>
                </a>

                <!-- Utilisateurs -->
                <div>
                    <button @click="openMenu = openMenu === 'users' ? null : 'users'" 
                            class="w-full flex items-center justify-between px-4 py-3 text-amber-100 hover:bg-amber-700 hover:bg-opacity-50 rounded-xl transition-all duration-200 group">
                        <div class="flex items-center">
                            <i class="fas fa-users text-amber-400 group-hover:text-amber-300 text-lg w-6"></i>
                            <span class="ml-3 font-medium">{{ $isFrench ? 'Utilisateurs' : 'Users' }}</span>
                        </div>
                        <i class="fas fa-chevron-down text-amber-400 transform transition-transform duration-200" 
                           :class="openMenu === 'users' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openMenu === 'users'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="ml-6 mt-2 space-y-1"
                         style="display: none;">
                        <a href="{{ route('admin.auto-ecole.users.index') }}" 
                           class="flex items-center px-4 py-2 text-amber-200 hover:text-white hover:bg-amber-700 hover:bg-opacity-30 rounded-lg transition-all text-sm">
                            <i class="fas fa-list text-xs w-4"></i>
                            <span class="ml-2">{{ $isFrench ? 'Liste' : 'List' }}</span>
                        </a>
                    </div>
                </div>

                <!-- Codes Caisse -->
                <div>
                    <button @click="openMenu = openMenu === 'codes' ? null : 'codes'" 
                            class="w-full flex items-center justify-between px-4 py-3 text-amber-100 hover:bg-amber-700 hover:bg-opacity-50 rounded-xl transition-all duration-200 group">
                        <div class="flex items-center">
                            <i class="fas fa-ticket-alt text-amber-400 group-hover:text-amber-300 text-lg w-6"></i>
                            <span class="ml-3 font-medium">{{ $isFrench ? 'Codes Caisse' : 'Cash Codes' }}</span>
                        </div>
                        <i class="fas fa-chevron-down text-amber-400 transform transition-transform duration-200" 
                           :class="openMenu === 'codes' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openMenu === 'codes'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="ml-6 mt-2 space-y-1"
                         style="display: none;">
                        <a href="{{ route('admin.auto-ecole.codes-caisse.index') }}" 
                           class="flex items-center px-4 py-2 text-amber-200 hover:text-white hover:bg-amber-700 hover:bg-opacity-30 rounded-lg transition-all text-sm">
                            <i class="fas fa-list text-xs w-4"></i>
                            <span class="ml-2">{{ $isFrench ? 'Liste' : 'List' }}</span>
                        </a>
                        <a href="{{ route('admin.auto-ecole.codes-caisse.create') }}" 
                           class="flex items-center px-4 py-2 text-amber-200 hover:text-white hover:bg-amber-700 hover:bg-opacity-30 rounded-lg transition-all text-sm">
                            <i class="fas fa-plus text-xs w-4"></i>
                            <span class="ml-2">{{ $isFrench ? 'Créer' : 'Create' }}</span>
                        </a>
                    </div>
                </div>

                <!-- Sessions -->
                <div>
                    <button @click="openMenu = openMenu === 'sessions' ? null : 'sessions'" 
                            class="w-full flex items-center justify-between px-4 py-3 text-amber-100 hover:bg-amber-700 hover:bg-opacity-50 rounded-xl transition-all duration-200 group">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt text-amber-400 group-hover:text-amber-300 text-lg w-6"></i>
                            <span class="ml-3 font-medium">{{ $isFrench ? 'Sessions' : 'Sessions' }}</span>
                        </div>
                        <i class="fas fa-chevron-down text-amber-400 transform transition-transform duration-200" 
                           :class="openMenu === 'sessions' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openMenu === 'sessions'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="ml-6 mt-2 space-y-1"
                         style="display: none;">
                        <a href="{{ route('admin.auto-ecole.sessions.index') }}" 
                           class="flex items-center px-4 py-2 text-amber-200 hover:text-white hover:bg-amber-700 hover:bg-opacity-30 rounded-lg transition-all text-sm">
                            <i class="fas fa-list text-xs w-4"></i>
                            <span class="ml-2">{{ $isFrench ? 'Liste' : 'List' }}</span>
                        </a>
                        <a href="{{ route('admin.auto-ecole.sessions.create') }}" 
                           class="flex items-center px-4 py-2 text-amber-200 hover:text-white hover:bg-amber-700 hover:bg-opacity-30 rounded-lg transition-all text-sm">
                            <i class="fas fa-plus text-xs w-4"></i>
                            <span class="ml-2">{{ $isFrench ? 'Créer' : 'Create' }}</span>
                        </a>
                    </div>
                </div>

                <!-- Jours Pratique -->
                <div>
                    <button @click="openMenu = openMenu === 'jours-pratique' ? null : 'jours-pratique'" 
                            class="w-full flex items-center justify-between px-4 py-3 text-amber-100 hover:bg-amber-700 hover:bg-opacity-50 rounded-xl transition-all duration-200 group">
                        <div class="flex items-center">
                            <i class="fas fa-car-side text-amber-400 group-hover:text-amber-300 text-lg w-6"></i>
                            <span class="ml-3 font-medium">{{ $isFrench ? 'Jours Pratique' : 'Practice Days' }}</span>
                        </div>
                        <i class="fas fa-chevron-down text-amber-400 transform transition-transform duration-200" 
                           :class="openMenu === 'jours-pratique' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openMenu === 'jours-pratique'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="ml-6 mt-2 space-y-1"
                         style="display: none;">
                        <a href="{{ route('admin.auto-ecole.jours-pratique.index') }}" 
                           class="flex items-center px-4 py-2 text-amber-200 hover:text-white hover:bg-amber-700 hover:bg-opacity-30 rounded-lg transition-all text-sm">
                            <i class="fas fa-list text-xs w-4"></i>
                            <span class="ml-2">{{ $isFrench ? 'Liste' : 'List' }}</span>
                        </a>
                        <a href="{{ route('admin.auto-ecole.jours-pratique.create') }}" 
                           class="flex items-center px-4 py-2 text-amber-200 hover:text-white hover:bg-amber-700 hover:bg-opacity-30 rounded-lg transition-all text-sm">
                            <i class="fas fa-plus text-xs w-4"></i>
                            <span class="ml-2">{{ $isFrench ? 'Créer' : 'Create' }}</span>
                        </a>
                    </div>
                </div>

                <!-- Centres d'Examen -->
                <div>
                    <button @click="openMenu = openMenu === 'centres-examen' ? null : 'centres-examen'" 
                            class="w-full flex items-center justify-between px-4 py-3 text-amber-100 hover:bg-amber-700 hover:bg-opacity-50 rounded-xl transition-all duration-200 group">
                        <div class="flex items-center">
                            <i class="fas fa-building text-amber-400 group-hover:text-amber-300 text-lg w-6"></i>
                            <span class="ml-3 font-medium">{{ $isFrench ? 'Centres d\'Examen' : 'Exam Centers' }}</span>
                        </div>
                        <i class="fas fa-chevron-down text-amber-400 transform transition-transform duration-200" 
                           :class="openMenu === 'centres-examen' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openMenu === 'centres-examen'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="ml-6 mt-2 space-y-1"
                         style="display: none;">
                        <a href="{{ route('admin.auto-ecole.centres-examen.index') }}" 
                           class="flex items-center px-4 py-2 text-amber-200 hover:text-white hover:bg-amber-700 hover:bg-opacity-30 rounded-lg transition-all text-sm">
                            <i class="fas fa-list text-xs w-4"></i>
                            <span class="ml-2">{{ $isFrench ? 'Liste' : 'List' }}</span>
                        </a>
                        <a href="{{ route('admin.auto-ecole.centres-examen.create') }}" 
                           class="flex items-center px-4 py-2 text-amber-200 hover:text-white hover:bg-amber-700 hover:bg-opacity-30 rounded-lg transition-all text-sm">
                            <i class="fas fa-plus text-xs w-4"></i>
                            <span class="ml-2">{{ $isFrench ? 'Créer' : 'Create' }}</span>
                        </a>
                    </div>
                </div>

                <!-- Cours -->
                <div>
                    <button @click="openMenu = openMenu === 'cours' ? null : 'cours'" 
                            class="w-full flex items-center justify-between px-4 py-3 text-amber-100 hover:bg-amber-700 hover:bg-opacity-50 rounded-xl transition-all duration-200 group">
                        <div class="flex items-center">
                            <i class="fas fa-book text-amber-400 group-hover:text-amber-300 text-lg w-6"></i>
                            <span class="ml-3 font-medium">{{ $isFrench ? 'Cours' : 'Courses' }}</span>
                        </div>
                        <i class="fas fa-chevron-down text-amber-400 transform transition-transform duration-200" 
                           :class="openMenu === 'cours' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openMenu === 'cours'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="ml-6 mt-2 space-y-1"
                         style="display: none;">
                        <a href="{{ route('admin.auto-ecole.cours.modules.index') }}" 
                           class="flex items-center px-4 py-2 text-amber-200 hover:text-white hover:bg-amber-700 hover:bg-opacity-30 rounded-lg transition-all text-sm">
                            <i class="fas fa-folder text-xs w-4"></i>
                            <span class="ml-2">{{ $isFrench ? 'Modules' : 'Modules' }}</span>
                        </a>
                    </div>
                </div>

                <!-- Quiz -->
                <div>
                    <button @click="openMenu = openMenu === 'quiz' ? null : 'quiz'" 
                            class="w-full flex items-center justify-between px-4 py-3 text-amber-100 hover:bg-amber-700 hover:bg-opacity-50 rounded-xl transition-all duration-200 group">
                        <div class="flex items-center">
                            <i class="fas fa-question-circle text-amber-400 group-hover:text-amber-300 text-lg w-6"></i>
                            <span class="ml-3 font-medium">{{ $isFrench ? 'Quiz' : 'Quizzes' }}</span>
                        </div>
                        <i class="fas fa-chevron-down text-amber-400 transform transition-transform duration-200" 
                           :class="openMenu === 'quiz' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openMenu === 'quiz'" 
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 -translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 -translate-y-2"
     class="ml-6 mt-2 space-y-1"
     style="display: none;">
    <a href="{{ route('admin.auto-ecole.quiz.index') }}" 
       class="flex items-center px-4 py-2 text-amber-200 hover:text-white hover:bg-amber-700 hover:bg-opacity-30 rounded-lg transition-all text-sm">
        <i class="fas fa-list text-xs w-4"></i>
        <span class="ml-2">{{ $isFrench ? 'Liste' : 'List' }}</span>
    </a>
    <a href="{{ route('admin.auto-ecole.quiz.create') }}" 
       class="flex items-center px-4 py-2 text-amber-200 hover:text-white hover:bg-amber-700 hover:bg-opacity-30 rounded-lg transition-all text-sm">
        <i class="fas fa-plus text-xs w-4"></i>
        <span class="ml-2">{{ $isFrench ? 'Créer' : 'Create' }}</span>
    </a>
</div>
</div>

<!-- Paiements -->
<div>
    <button @click="openMenu = openMenu === 'paiements' ? null : 'paiements'" 
            class="w-full flex items-center justify-between px-4 py-3 text-amber-100 hover:bg-amber-700 hover:bg-opacity-50 rounded-xl transition-all duration-200 group">
        <div class="flex items-center">
            <i class="fas fa-credit-card text-amber-400 group-hover:text-amber-300 text-lg w-6"></i>
            <span class="ml-3 font-medium">{{ $isFrench ? 'Paiements' : 'Payments' }}</span>
        </div>
        <i class="fas fa-chevron-down text-amber-400 transform transition-transform duration-200" 
           :class="openMenu === 'paiements' ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="openMenu === 'paiements'" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="ml-6 mt-2 space-y-1"
         style="display: none;">
        <a href="{{ route('admin.paiements.index') }}" 
           class="flex items-center px-4 py-2 text-amber-200 hover:text-white hover:bg-amber-700 hover:bg-opacity-30 rounded-lg transition-all text-sm">
            <i class="fas fa-list text-xs w-4"></i>
            <span class="ml-2">{{ $isFrench ? 'Liste' : 'List' }}</span>
        </a>
        <a href="{{ route('admin.paiements.statistiques') }}" 
           class="flex items-center px-4 py-2 text-amber-200 hover:text-white hover:bg-amber-700 hover:bg-opacity-30 rounded-lg transition-all text-sm">
            <i class="fas fa-chart-bar text-xs w-4"></i>
            <span class="ml-2">{{ $isFrench ? 'Statistiques' : 'Statistics' }}</span>
        </a>
    </div>
</div>


            
<!-- Configuration Paiement avec le même style -->
<div class="mt-2">
    <a href="{{ route('admin.auto-ecole.config-paiement.edit') }}" 
       class="flex items-center px-4 py-2 text-amber-200 hover:text-white hover:bg-amber-700 hover:bg-opacity-30 rounded-lg transition-all text-sm">
        <i class="fas fa-cog text-xs w-4"></i>
        <span class="ml-2">{{ $isFrench ? 'Configuration Paiement' : 'Payment Settings' }}</span>
    </a>
</div>

            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Mobile Menu Button -->
            <div class="lg:hidden flex items-center justify-between h-16 px-4 bg-white shadow-md">
                <button @click="sidebarOpen = true" class="text-amber-700 hover:text-amber-900 transition-colors">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <h1 class="text-lg font-bold text-amber-900">{{ $isFrench ? 'Auto-École' : 'Driving School' }}</h1>
                <div class="w-8"></div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
                
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm animate-fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm animate-fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                        <p class="text-red-800 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                <!-- Main Content Area -->
                <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
                    @yield('admin-content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white bg-opacity-90 backdrop-blur-sm border-t border-amber-200 py-4 px-4 sm:px-6 lg:px-8 flex-shrink-0">
                <div class="flex flex-col sm:flex-row items-center justify-between text-sm text-amber-700">
                    <p>&copy; {{ date('Y') }} {{ $isFrench ? 'Auto-École. Tous droits réservés.' : 'Driving School. All rights reserved.' }}</p>
                    <div class="flex items-center space-x-4 mt-2 sm:mt-0">
                        <a href="#" class="hover:text-amber-900 transition-colors">{{ $isFrench ? 'Aide' : 'Help' }}</a>
                        <span class="text-amber-400">•</span>
                        <a href="#" class="hover:text-amber-900 transition-colors">{{ $isFrench ? 'Confidentialité' : 'Privacy' }}</a>
                        <span class="text-amber-400">•</span>
                        <a href="#" class="hover:text-amber-900 transition-colors">{{ $isFrench ? 'Conditions' : 'Terms' }}</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
    .scrollbar-thin::-webkit-scrollbar {
        width: 6px;
    }
    .scrollbar-thumb-amber-700::-webkit-scrollbar-thumb {
        background-color: rgb(180, 83, 9);
        border-radius: 3px;
    }
    .scrollbar-track-amber-900::-webkit-scrollbar-track {
        background-color: rgb(120, 53, 15);
    }
</style>

<script>
    // Assurer que Alpine.js est initialisé correctement
    document.addEventListener('alpine:init', () => {
        console.log('Alpine.js initialized');
    });
</script>

@endsection