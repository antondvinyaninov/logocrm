<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .tooltip-wrapper {
                position: relative;
            }
            .tooltip-wrapper .tooltip {
                position: fixed;
                margin-left: 0.5rem;
                padding: 0.5rem 0.75rem;
                background-color: #1f2937;
                color: white;
                border-radius: 0.375rem;
                font-size: 0.875rem;
                white-space: nowrap;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.2s;
                z-index: 9999;
            }
            .tooltip-wrapper:hover .tooltip {
                opacity: 1;
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.tooltip-wrapper').forEach(wrapper => {
                    const tooltip = wrapper.querySelector('.tooltip');
                    if (tooltip) {
                        wrapper.addEventListener('mouseenter', function(e) {
                            const rect = wrapper.getBoundingClientRect();
                            tooltip.style.left = (rect.right + 8) + 'px';
                            tooltip.style.top = (rect.top + rect.height / 2) + 'px';
                            tooltip.style.transform = 'translateY(-50%)';
                        });
                    }
                });
            });
        </script>
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: true }">
        <div class="min-h-screen bg-gray-100">
            <!-- Page Content -->
            <div class="flex h-screen">
                <!-- Sidebar -->
                <aside :class="sidebarOpen ? 'w-64' : 'w-16'" class="bg-gray-900 shadow-lg flex flex-col transition-all duration-300 flex-shrink-0 h-screen overflow-y-auto overflow-x-hidden relative">
                    
                    <!-- Logo -->
                    <div class="py-3 border-b border-gray-800" :class="sidebarOpen ? 'px-4 flex items-center gap-3' : 'px-2 flex items-center justify-center'">
                        <a href="{{ route('dashboard') }}" class="flex-shrink-0">
                            <x-application-logo class="block h-9 w-auto fill-current text-white" />
                        </a>
                        
                        <div x-show="sidebarOpen" x-transition class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">SuperAdmin</p>
                            <p class="text-xs text-gray-400 truncate">Панель управления</p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 mt-5 px-2">
                        <div class="tooltip-wrapper">
                            <a href="{{ route('dashboard') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span x-show="sidebarOpen" x-transition>Главная</span>
                            </a>
                            <div x-show="!sidebarOpen" class="tooltip">Главная</div>
                        </div>
                        
                        <div class="tooltip-wrapper mt-1">
                            <a href="{{ route('admin.organizations.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('admin.organizations.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span x-show="sidebarOpen" x-transition>Организации</span>
                            </a>
                            <div x-show="!sidebarOpen" class="tooltip">Организации</div>
                        </div>
                        
                        <div class="tooltip-wrapper mt-1">
                            <a href="{{ route('admin.users.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span x-show="sidebarOpen" x-transition>Пользователи</span>
                            </a>
                            <div x-show="!sidebarOpen" class="tooltip">Пользователи</div>
                        </div>
                        
                        <div class="tooltip-wrapper mt-1">
                            <a href="{{ route('admin.settings.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span x-show="sidebarOpen" x-transition>Настройки</span>
                            </a>
                            <div x-show="!sidebarOpen" class="tooltip">Настройки</div>
                        </div>
                    </nav>

                    <!-- User Profile at Bottom -->
                    <div class="border-t border-gray-800 p-3">
                        <div class="flex items-center" :class="sidebarOpen ? '' : 'justify-center'">
                            <div class="flex-shrink-0">
                                <div class="h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ substr(Auth::user()->name, 0, 2) }}</span>
                                </div>
                            </div>
                            <div x-show="sidebarOpen" x-transition class="ml-3 flex-1">
                                <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div x-show="sidebarOpen" x-transition class="mt-2 space-y-1">
                            <!-- Logout and Toggle Button Row -->
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3 py-1 text-sm text-red-400 hover:bg-gray-800 rounded-md">
                                        Выйти
                                    </button>
                                </form>
                                <button @click="sidebarOpen = !sidebarOpen" class="flex-shrink-0 p-2 text-gray-400 hover:bg-gray-800 rounded-md border border-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Toggle Button for Collapsed State -->
                        <div x-show="!sidebarOpen" x-transition class="mt-2">
                            <button @click="sidebarOpen = !sidebarOpen" class="w-full flex items-center justify-center p-2 text-gray-400 hover:bg-gray-800 rounded-md border border-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </aside>

                <!-- Main Content Area -->
                <div class="flex-1 flex flex-col h-screen">
                    <!-- Header -->
                    <header class="bg-white border-b border-gray-200 px-6 flex-shrink-0" style="padding-top: 0.875rem; padding-bottom: 0.875rem;">
                        @isset($header)
                            {{ $header }}
                        @else
                            <div class="flex items-center justify-between">
                                <h1 class="text-2xl font-semibold text-gray-800">
                                    @if(request()->routeIs('dashboard'))
                                        Панель управления
                                    @elseif(request()->routeIs('admin.organizations.*'))
                                        Организации
                                    @elseif(request()->routeIs('admin.users.*'))
                                        Пользователи
                                    @elseif(request()->routeIs('admin.settings.*'))
                                        Настройки
                                    @endif
                                </h1>
                            </div>
                        @endisset
                    </header>

                    <!-- Main Content -->
                    <main class="flex-1 overflow-y-auto bg-gray-100 p-6">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
