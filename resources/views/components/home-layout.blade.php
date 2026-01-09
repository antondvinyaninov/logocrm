<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
    <body class="font-sans antialiased" x-data="{ sidebarOpen: true, clientsOpen: false }">
        <div class="min-h-screen bg-gray-100">
            <!-- Page Content -->
            <div class="flex h-screen">
                <!-- Sidebar -->
                <aside :class="sidebarOpen ? 'w-64' : 'w-16'" class="bg-white shadow-lg flex flex-col transition-all duration-300 flex-shrink-0 h-screen overflow-y-auto overflow-x-hidden relative">
                    
                    <!-- Logo -->
                    <div class="py-3 border-b border-gray-200" :class="sidebarOpen ? 'px-4 flex items-center gap-3' : 'px-2 flex items-center justify-center'">
                        @auth
                            @if(Auth::user()->isSuperAdmin())
                                <a href="{{ route('dashboard') }}" class="flex-shrink-0">
                                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                                </a>
                            @else
                                <a href="{{ route('home') }}" class="flex-shrink-0">
                                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                                </a>
                            @endif
                        @else
                            <a href="/" class="flex-shrink-0">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        @endauth
                        
                        @auth
                            <div x-show="sidebarOpen" x-transition class="flex-1 min-w-0">
                                @if(Auth::user()->isOrganization() && Auth::user()->organization)
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->organization->name }}</p>
                                @elseif(Auth::user()->isSpecialist())
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                    @if(Auth::user()->specialistProfile)
                                        @if(Auth::user()->specialistProfile->position)
                                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->specialistProfile->position }}</p>
                                        @else
                                            <p class="text-xs text-gray-500 truncate">Частный специалист</p>
                                        @endif
                                    @endif
                                @elseif(Auth::user()->isParent())
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                @endif
                            </div>
                        @endauth
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 mt-5 px-2">
                        @auth
                            @if(Auth::user()->isOrganization())
                                <div class="tooltip-wrapper">
                                    <a href="{{ route('home') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('home') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Главная</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Главная</div>
                                </div>
                                
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('analytics.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('analytics.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Аналитика</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Аналитика</div>
                                </div>
                                
                                <!-- Клиенты - раскрывающийся список -->
                                <div class="mt-1">
                                    <div class="tooltip-wrapper">
                                        <button @click="clientsOpen = !clientsOpen" class="w-full group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('children.*') || request()->routeIs('parents.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                            <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            <span x-show="sidebarOpen" x-transition class="flex-1 text-left">Клиенты</span>
                                            <svg x-show="sidebarOpen" :class="clientsOpen ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="!sidebarOpen" class="tooltip">Клиенты</div>
                                    </div>
                                    
                                    <!-- Подменю -->
                                    <div x-show="clientsOpen && sidebarOpen" x-transition class="mt-1 bg-indigo-50 rounded-lg py-2 px-2 space-y-1">
                                        <a href="{{ route('parents.index') }}" class="block py-2 px-4 text-sm rounded-md {{ request()->routeIs('parents.*') ? 'bg-indigo-100 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-indigo-100' }}">
                                            Родители
                                        </a>
                                        <a href="{{ route('children.index') }}" class="block py-2 px-4 text-sm rounded-md {{ request()->routeIs('children.*') ? 'bg-indigo-100 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-indigo-100' }}">
                                            Дети
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('sessions.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('sessions.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Занятия</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Занятия</div>
                                </div>
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('payments.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('payments.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Финансы</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Финансы</div>
                                </div>
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('reviews.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('reviews.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Отзывы</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Отзывы</div>
                                </div>
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('team.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('team.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Специалисты</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Специалисты</div>
                                </div>
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('services.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('services.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Услуги</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Услуги</div>
                                </div>
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('settings.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('settings.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Настройки</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Настройки</div>
                                </div>
                            @elseif(Auth::user()->isSpecialist())
                                <div class="tooltip-wrapper">
                                    <a href="{{ route('home') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('home') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Главная</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Главная</div>
                                </div>
                                
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('analytics.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('analytics.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Аналитика</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Аналитика</div>
                                </div>
                                
                                <!-- Клиенты - раскрывающийся список -->
                                <div class="mt-1">
                                    <div class="tooltip-wrapper">
                                        <button @click="clientsOpen = !clientsOpen" class="w-full group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('children.*') || request()->routeIs('parents.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                            <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            <span x-show="sidebarOpen" x-transition class="flex-1 text-left">Клиенты</span>
                                            <svg x-show="sidebarOpen" :class="clientsOpen ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="!sidebarOpen" class="tooltip">Клиенты</div>
                                    </div>
                                    
                                    <!-- Подменю -->
                                    <div x-show="clientsOpen && sidebarOpen" x-transition class="mt-1 bg-indigo-50 rounded-lg py-2 px-2 space-y-1">
                                        <a href="{{ route('parents.index') }}" class="block py-2 px-4 text-sm rounded-md {{ request()->routeIs('parents.*') ? 'bg-indigo-100 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-indigo-100' }}">
                                            Родители
                                        </a>
                                        <a href="{{ route('children.index') }}" class="block py-2 px-4 text-sm rounded-md {{ request()->routeIs('children.*') ? 'bg-indigo-100 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-indigo-100' }}">
                                            Дети
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('sessions.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('sessions.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Занятия</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Занятия</div>
                                </div>
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('homeworks.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('homeworks.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Домашние задания</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Домашние задания</div>
                                </div>
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('payments.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('payments.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Финансы</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Финансы</div>
                                </div>
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('reviews.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('reviews.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Отзывы</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Отзывы</div>
                                </div>
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('specialists.edit') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('specialists.edit') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Профиль специалиста</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Профиль специалиста</div>
                                </div>
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('services.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('services.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Услуги</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Услуги</div>
                                </div>
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('settings.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('settings.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Настройки</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Настройки</div>
                                </div>
                            @elseif(Auth::user()->isParent())
                                <div class="tooltip-wrapper">
                                    <a href="{{ route('home') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('home') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Главная</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Главная</div>
                                </div>
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('analytics.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('analytics.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Аналитика</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Аналитика</div>
                                </div>
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('children.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('children.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Дети</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Дети</div>
                                </div>
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('sessions.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('sessions.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Занятия</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Занятия</div>
                                </div>
                                <div class="tooltip-wrapper mt-1">
                                    <a href="{{ route('homeworks.index') }}" class="group flex items-center py-1 text-sm font-medium rounded-md {{ request()->routeIs('homeworks.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" :class="sidebarOpen ? 'px-4' : 'justify-center'">
                                        <svg class="h-6 w-6 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-transition>Домашние задания</span>
                                    </a>
                                    <div x-show="!sidebarOpen" class="tooltip">Домашние задания</div>
                                </div>
                            @endif
                        @endauth
                    </nav>

                    <!-- Mini Calendar -->
                    @auth
                    <div x-show="sidebarOpen" x-transition class="px-4 py-4 border-t border-gray-200">
                        <div class="mb-3">
                            <!-- Заголовок с месяцем и навигацией -->
                            <div class="flex justify-between items-center mb-3">
                                @php
                                    $calendarDate = isset($currentDate) ? $currentDate : now();
                                @endphp
                                <a href="{{ request()->routeIs('calendar.*') ? route(request()->route()->getName(), ['date' => $calendarDate->copy()->subMonth()->format('Y-m-d')]) : route('calendar.index', ['date' => $calendarDate->copy()->subMonth()->format('Y-m-d')]) }}" class="p-1 hover:bg-gray-100 rounded">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                                <span class="text-sm font-semibold text-gray-800">{{ $calendarDate->translatedFormat('F Y') }}</span>
                                <a href="{{ request()->routeIs('calendar.*') ? route(request()->route()->getName(), ['date' => $calendarDate->copy()->addMonth()->format('Y-m-d')]) : route('calendar.index', ['date' => $calendarDate->copy()->addMonth()->format('Y-m-d')]) }}" class="p-1 hover:bg-gray-100 rounded">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                            
                            <!-- Календарная сетка -->
                            <div class="grid grid-cols-7 gap-1">
                                <!-- Дни недели -->
                                @foreach(['пн', 'вт', 'ср', 'чт', 'пт', 'сб', 'вс'] as $day)
                                    <div class="text-center text-xs text-gray-500 font-medium pb-1">{{ $day }}</div>
                                @endforeach
                                
                                <!-- Дни месяца -->
                                @php
                                    $startOfMonth = $calendarDate->copy()->startOfMonth();
                                    $endOfMonth = $calendarDate->copy()->endOfMonth();
                                    $startDay = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
                                    $endDay = $endOfMonth->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
                                    $currentDay = $startDay->copy();
                                @endphp
                                
                                @while($currentDay <= $endDay)
                                    @php
                                        $isCurrentMonth = $currentDay->month === $calendarDate->month;
                                        $isToday = $currentDay->isToday();
                                        $isSelected = isset($currentDate) && $currentDay->isSameDay($currentDate);
                                    @endphp
                                    
                                    <a href="{{ request()->routeIs('calendar.*') ? route(request()->route()->getName(), ['date' => $currentDay->format('Y-m-d')]) : route('calendar.index', ['date' => $currentDay->format('Y-m-d')]) }}" 
                                       class="text-center py-1 text-xs rounded hover:bg-gray-100 transition-colors {{ $isCurrentMonth ? 'text-gray-900' : 'text-gray-400' }} {{ $isSelected ? 'bg-blue-600 text-white hover:bg-blue-700' : ($isToday ? 'bg-yellow-400 hover:bg-yellow-500 font-semibold' : '') }}">
                                        {{ $currentDay->day }}
                                    </a>
                                    
                                    @php
                                        $currentDay->addDay();
                                    @endphp
                                @endwhile
                            </div>
                        </div>
                    </div>
                    @endauth

                    <!-- User Profile at Bottom -->
                    @auth
                    <div class="border-t border-gray-200 p-3">
                        <div class="flex items-center" :class="sidebarOpen ? '' : 'justify-center'">
                            <div class="flex-shrink-0">
                                <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-600 font-semibold text-sm">{{ substr(Auth::user()->name, 0, 2) }}</span>
                                </div>
                            </div>
                            <div x-show="sidebarOpen" x-transition class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div x-show="sidebarOpen" x-transition class="mt-2 space-y-1">
                            <!-- Logout and Toggle Button Row -->
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3 py-1 text-sm text-red-600 hover:bg-red-50 rounded-md">
                                        Выйти
                                    </button>
                                </form>
                                <button @click="sidebarOpen = !sidebarOpen" class="flex-shrink-0 p-2 text-gray-700 hover:bg-gray-50 rounded-md border border-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Toggle Button for Collapsed State -->
                        <div x-show="!sidebarOpen" x-transition class="mt-2">
                            <button @click="sidebarOpen = !sidebarOpen" class="w-full flex items-center justify-center p-2 text-gray-700 hover:bg-gray-50 rounded-md border border-gray-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endauth
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
                                    @if(request()->routeIs('home'))
                                        Главная
                                    @elseif(request()->routeIs('team.*'))
                                        Команда
                                    @elseif(request()->routeIs('children.*'))
                                        Клиенты
                                    @elseif(request()->routeIs('sessions.*'))
                                        Занятия
                                    @elseif(request()->routeIs('calendar.*'))
                                        Календарь
                                    @elseif(request()->routeIs('homeworks.*'))
                                        Домашние задания
                                    @elseif(request()->routeIs('payments.*'))
                                        Финансы
                                    @elseif(request()->routeIs('reviews.*'))
                                        Отзывы
                                    @elseif(request()->routeIs('settings.*'))
                                        Настройки
                                    @endif
                                </h1>
                            </div>
                        @endisset
                    </header>

                    <!-- Main Content -->
                    <main class="flex-1 overflow-y-auto bg-gray-100 {{ isset($noPadding) && $noPadding ? '' : 'p-6' }}">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
