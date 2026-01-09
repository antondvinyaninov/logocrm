<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    @auth
                        @if(Auth::user()->isSuperAdmin())
                            <a href="{{ route('dashboard') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        @else
                            <a href="{{ route('home') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        @endif
                    @else
                        <a href="/">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </a>
                    @endauth
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        @if(Auth::user()->isSuperAdmin())
                        {{-- SuperAdmin: управление платформой --}}
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('admin.organizations.index')" :active="request()->routeIs('admin.organizations.*')">
                            {{ __('Организации') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Пользователи') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
                            {{ __('Настройки платформы') }}
                        </x-nav-link>
                        
                    @elseif(Auth::user()->isOrganization())
                        {{-- Organization: управление центром --}}
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('Главная') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('team.index')" :active="request()->routeIs('team.*')">
                            {{ __('Команда') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('children.index')" :active="request()->routeIs('children.*')">
                            {{ __('Клиенты') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('sessions.index')" :active="request()->routeIs('sessions.*')">
                            {{ __('Занятия') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('leads.index')" :active="request()->routeIs('leads.*')">
                            {{ __('Заявки') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">
                            {{ __('Финансы') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')">
                            {{ __('Настройки') }}
                        </x-nav-link>
                        
                    @elseif(Auth::user()->isSpecialist())
                        {{-- Specialist: работа с клиентами --}}
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('Главная') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('children.index')" :active="request()->routeIs('children.*')">
                            {{ __('Клиенты') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('sessions.index')" :active="request()->routeIs('sessions.*')">
                            {{ __('Занятия') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('homeworks.index')" :active="request()->routeIs('homeworks.*')">
                            {{ __('Домашние задания') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('leads.index')" :active="request()->routeIs('leads.*')">
                            {{ __('Заявки') }}
                        </x-nav-link>
                        
                    @elseif(Auth::user()->isParent())
                        {{-- Parent: просмотр информации о детях --}}
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('Главная') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('children.index')" :active="request()->routeIs('children.*')">
                            {{ __('Дети') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('sessions.index')" :active="request()->routeIs('sessions.*')">
                            {{ __('Занятия') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('homeworks.index')" :active="request()->routeIs('homeworks.*')">
                            {{ __('Домашние задания') }}
                        </x-nav-link>
                    @endif
                    @else
                        {{-- Гости: публичные страницы --}}
                        <x-nav-link :href="route('specialists.catalog')" :active="request()->routeIs('specialists.*')">
                            {{ __('Специалисты') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Личный профиль
                        </x-dropdown-link>

                        @if(Auth::user()->isOrganization())
                            <x-dropdown-link :href="route('organizations.edit-own')">
                                Профиль организации
                            </x-dropdown-link>
                        @endif

                        @if(Auth::user()->isSpecialist())
                            <x-dropdown-link :href="route('specialists.edit')">
                                Профиль специалиста
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                    {{-- Кнопки для гостей --}}
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900 mr-4">
                        Войти
                    </a>
                    <a href="{{ route('register') }}" class="text-sm px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">
                        Регистрация
                    </a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
            @if(Auth::user()->isSuperAdmin())
                {{-- SuperAdmin: управление платформой --}}
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('admin.organizations.index')" :active="request()->routeIs('admin.organizations.*')">
                    {{ __('Организации') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Пользователи') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
                    {{ __('Настройки платформы') }}
                </x-responsive-nav-link>
                
            @elseif(Auth::user()->isOrganization())
                {{-- Organization: управление центром --}}
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Главная') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('team.index')" :active="request()->routeIs('team.*')">
                    {{ __('Команда') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('children.index')" :active="request()->routeIs('children.*')">
                    {{ __('Клиенты') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('sessions.index')" :active="request()->routeIs('sessions.*')">
                    {{ __('Занятия') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('leads.index')" :active="request()->routeIs('leads.*')">
                    {{ __('Заявки') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">
                    {{ __('Финансы') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')">
                    {{ __('Настройки') }}
                </x-responsive-nav-link>
                
            @elseif(Auth::user()->isSpecialist())
                {{-- Specialist: работа с клиентами --}}
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Главная') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('children.index')" :active="request()->routeIs('children.*')">
                    {{ __('Клиенты') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('sessions.index')" :active="request()->routeIs('sessions.*')">
                    {{ __('Занятия') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('homeworks.index')" :active="request()->routeIs('homeworks.*')">
                    {{ __('Домашние задания') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('leads.index')" :active="request()->routeIs('leads.*')">
                    {{ __('Заявки') }}
                </x-responsive-nav-link>
                
            @elseif(Auth::user()->isParent())
                {{-- Parent: просмотр информации о детях --}}
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Главная') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('children.index')" :active="request()->routeIs('children.*')">
                    {{ __('Дети') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('sessions.index')" :active="request()->routeIs('sessions.*')">
                    {{ __('Занятия') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('homeworks.index')" :active="request()->routeIs('homeworks.*')">
                    {{ __('Домашние задания') }}
                </x-responsive-nav-link>
            @endif
            @else
                {{-- Гости: публичные страницы --}}
                <x-responsive-nav-link :href="route('specialists.catalog')" :active="request()->routeIs('specialists.*')">
                    {{ __('Специалисты') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Личный профиль
                </x-responsive-nav-link>

                @if(Auth::user()->isOrganization())
                    <x-responsive-nav-link :href="route('organizations.edit-own')">
                        Профиль организации
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->isSpecialist())
                    <x-responsive-nav-link :href="route('specialists.edit')">
                        Профиль специалиста
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>
