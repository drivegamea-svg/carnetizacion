<x-guest-layout>
    <!-- Session Status -->
 
    <form method="post" class="login-form" id="formLogin" action="{{ route('login') }}">
        @csrf
        <input type="hidden" name="recaptcha_token" id="recaptchaToken">
        <div class="input-group">
            <span class="input-icon">
                <i class="fas fa-user"></i>
            </span>
            <input id="email" type="email" name="email" class="form-input" placeholder="Introduce tu usuario"
                :value="old('email')" required autofocus autocomplete="username" placeholder="Email Address" required>
        </div>

        <div class="input-group">
            <span class="input-icon">
                <i class="fas fa-lock"></i>
            </span>
            <input id="password" type="password" name="password" class="form-input"
                placeholder="Introduce tu contraseña" required autocomplete="current-password">

            <span class="password-toggle" id="togglePassword">
                <i class="fas fa-eye"></i>
            </span>
           
        </div>

        <button type="submit" class="login-button">INICIAR SESIÓN</button>
    </form>
</x-guest-layout>
