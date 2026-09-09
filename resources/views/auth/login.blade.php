<x-guest-layout>
    <h1 class="auth-title">Bienvenido de nuevo</h1>
    <p class="auth-subtitle">Inicia sesión para continuar en Reporte Ciber</p>

    @if (session('status'))
        <div class="auth-status">✅ {{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Documento o Email -->
        <div class="auth-field">
            <label for="login" class="rc-label">🧑‍🎓 Documento o correo</label>
            <input
                id="login"
                type="text"
                name="login"
                value="{{ old('login') }}"
                class="rc-input"
                placeholder="Tu documento o correo"
                required
                autofocus
                autocomplete="username"
            >
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="auth-field">
            <label for="password" class="rc-label">🔒 Contraseña</label>
            <input
                id="password"
                type="password"
                name="password"
                class="rc-input"
                placeholder="Tu contraseña"
                required
                autocomplete="current-password"
            >
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="auth-row">
            <label for="remember_me" class="auth-checkbox">
                <input id="remember_me" type="checkbox" name="remember">
                Recordarme
            </label>

            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <button type="submit" class="auth-submit">
            Iniciar sesión
        </button>
    </form>

    <p class="auth-footer">
        ¿Vives una situación de acoso? <span style="color: #18629b;">Reporta un caso</span> — puede ser anónimo.
    </p>
</x-guest-layout>
