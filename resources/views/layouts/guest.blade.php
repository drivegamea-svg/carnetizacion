<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Acceso | Sistema de Carnetización CIZEE</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Sistema de Carnetización CIZEE - Plataforma de acceso para la gestión y emisión de credenciales.">
    <meta name="keywords" content="carnetización, CIZEE, login, acceso, sistema de gestión">

    <meta property="og:title" content="Acceso al Sistema de Carnetización CIZEE">
    
    <meta property="og:description" content="Inicia sesión para acceder a la plataforma de gestión de credenciales.">
    
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    
    <meta property="og:image" content="{{ asset('logueo/img/CARNETIZACION.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ asset('logueo/img/CARNETIZACION.png') }}">
    
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logueo/img/CARNETIZACION.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('logueo/img/CARNETIZACION.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="stylesheet" href="{{ asset('logueo/css/login.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://kit-free.fontawesome.com/releases/latest/css/free.min.css">
</head>



<body class="login-body">
    <div class="login-wrapper">
        <div class="login-container">

            <div class="login-header">
                <img src="{{ asset('logueo/img/CARNETIZACION.png') }}" alt="LOGO CIZEE" width="150px">

                <h1 class="login-title">SISTEMA DE CARNETIZACIÓN</h1>
                <p class="login-subtitle">CIZEE</p>

                
                <div class="login-card">
                    <div class="login-card-content">
                        <p class="login-instruction">
                            <i class="fas fa-info-circle"></i> Por favor, introduce tus credenciales para iniciar sesión
                        </p>

                        <p>
                                   <x-auth-session-status :status="session('status')" class="error-message password-error" />
                <x-input-error :messages="$errors->get('email')" class="error-message email-error" />
                <x-input-error :messages="$errors->get('password')" class="error-message password-error" />
                        </p>

                        <!-- BEGIN login-content -->
                        <div class="login-content">
                            {{ $slot }}
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <script>
            // Mostrar/Ocultar contraseña
            document.getElementById('togglePassword').addEventListener('click', function() {
                var passwordInput = document.getElementById('password');
                var icon = this.querySelector('i');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        </script>
</body>

</html>
