<nav x-data="{ open: false }" class="nav-bar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Contenedor con tres columnas: Logo | Enlaces centrados | Usuario --}}
        <div class="flex items-center h-16">

            {{-- COLUMNA IZQUIERDA: Logo --}}
            <div class="flex items-center shrink-0">
                <a href="{{ route('dashboard') }}" class="nav-brand">
                    🛡️ Reporte Ciber
                </a>
            </div>

            {{-- COLUMNA CENTRO: Enlaces (centrados) --}}
            <div class="hidden sm:flex sm:flex-1 sm:justify-center sm:items-center sm:space-x-8">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                    🏠 {{ __('Dashboard') }}
                </a>

                @if(auth()->user()->isCoordinador())
                    <a href="{{ route('estudiantes.importar') }}"
                       class="nav-link {{ request()->routeIs('estudiantes.importar') ? 'is-active' : '' }}">
                        📥 {{ __('Importar Estudiantes') }}
                    </a>
                @endif

                @if(auth()->user()->isEstudiante())
                    <a href="{{ route('estudiante.dashboard') }}"
                       class="nav-link {{ request()->routeIs('estudiante.dashboard') ? 'is-active' : '' }}">
                        🎓 {{ __('Mi Panel') }}
                    </a>
                @endif
            </div>

            {{-- COLUMNA DERECHA: Usuario --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                    <div @click="open = ! open">
                        <button class="nav-user-btn">
                            <span class="nav-icon">👤</span>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="nav-dropdown ltr:origin-top-right rtl:origin-top-left end-0"
                         style="display: none;"
                         @click="open = false">
                        <a href="{{ route('profile.edit') }}" class="nav-dropdown-link">
                            👤 {{ __('Profile') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" class="nav-dropdown-link"
                               onclick="event.preventDefault(); this.closest('form').submit();">
                                🚪 {{ __('Log Out') }}
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            {{-- BOTÓN HAMBURGUESA (móvil) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="nav-burger">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div> {{-- fin de .flex items-center h-16 --}}
    </div> {{-- fin del contenedor --}}

    {{-- MENÚ MÓVIL (sin cambios, solo ajuste de clases) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="nav-link-mobile {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                <span class="nav-icon">🏠</span>
                <span>{{ __('Dashboard') }}</span>
            </a>

            @if(auth()->user()->isCoordinador())
                <a href="{{ route('estudiantes.importar') }}"
                   class="nav-link-mobile {{ request()->routeIs('estudiantes.importar') ? 'is-active' : '' }}">
                    <span class="nav-icon">📥</span>
                    <span>{{ __('Importar Estudiantes') }}</span>
                </a>
            @endif

            @if(auth()->user()->isEstudiante())
                <a href="{{ route('estudiante.dashboard') }}"
                   class="nav-link-mobile {{ request()->routeIs('estudiante.dashboard') ? 'is-active' : '' }}">
                    <span class="nav-icon">🎓</span>
                    <span>{{ __('Mi Panel') }}</span>
                </a>
            @endif
        </div>

        <div class="nav-mobile-footer px-4">
            <div class="nav-mobile-name">{{ Auth::user()->name }}</div>
            <div class="nav-mobile-sub">
                {{ Auth::user()->email ?? Auth::user()->doc ?? 'Sin identificador' }}
            </div>
            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="nav-link-mobile">
                    👤 {{ __('Profile') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="nav-link-mobile"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        🚪 {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>