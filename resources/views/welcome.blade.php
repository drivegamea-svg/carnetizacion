<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Sistema de carnets - GAMEA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="Sistema de gestión de carnets para la CIZEE" />
    <meta name="author" content="CIZEE" />

    <!-- ================== BEGIN core-css ================== -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('pagina/assets/css/one-page-parallax/vendor.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('pagina/assets/css/one-page-parallax/app.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('pagina/assets/css/one-page-parallax/personal.css') }}" rel="stylesheet" />
    <!-- ================== END core-css ================== -->
</head>

<body data-bs-spy='scroll' data-bs-target='#header' data-bs-offset='51' class="theme-institucional">
    <!-- begin #page-container -->
    <div id="page-container" class="fade">
        <!-- begin #header -->
        <div id="header" class="header navbar navbar-transparent navbar-fixed-top navbar-expand-lg">
            <!-- begin container -->
            <div class="container">
                <!-- begin navbar-brand -->
                <a href="#home" class="navbar-brand d-flex align-items-center">
                    <img src="{{ asset('pagina/assets/img/logos/logoBlanco.png') }}" alt="GAMEA"
                        style="height: 24px;">
                    <span class="brand-text ms-2">
                        USyC
                    </span>
                </a>
                <!-- end navbar-brand -->
                <!-- begin navbar-toggle -->
                <button type="button" class="navbar-toggle collapsed" data-bs-toggle="collapse" data-bs-target="#header-navbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- end navbar-header -->
                <!-- begin navbar-collapse -->
                <div class="collapse navbar-collapse " id="header-navbar">
                    <ul class="nav navbar-nav ms-auto ">
                        <li class="nav-item"><a class="nav-link active" href="#home" data-click="scroll-to-target">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="#procesos"
                                data-click="scroll-to-target">Procesos de Contratación</a></li>
                        <li class="nav-item"><a class="nav-link" href="#requisitos"
                                data-click="scroll-to-target">Requisitos</a></li>
                        <li class="nav-item"><a class="nav-link" href="#consultar"
                                data-click="scroll-to-target">Consultar estado</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" data-click="scroll-to-target">Institucional <b class="caret"></b></a>
                            <div class="dropdown-menu dropdown-menu-left animate__animated animate__fadeInDown">
                                <a class="dropdown-item" href="#unidad-contratacion" data-click="scroll-to-target">Unidad de Contratación</a>
                                <a class="dropdown-item" href="#mision-vision" data-click="scroll-to-target">Misión y Visión</a>
                                <a class="dropdown-item" href="#normativa" data-click="scroll-to-target">Normativa</a>
                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#contacto"
                                data-click="scroll-to-target">Contacto</a></li>
                        
                        <!-- Login/Dashboard -->
                        @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="nav-link">Panel</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">Iniciar sesión</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="nav-link">Registrate</a>
                                </li>
                            @endif
                        @endauth
                        @endif
                    </ul>
                </div>
                <!-- end navbar-collapse -->
            </div>
            <!-- end container -->
        </div>
        <!-- end #header -->

        <!-- begin #home -->
        <div id="home" class="content has-bg home">
            <div class="content-bg"
                style="background-image: url({{ asset('color-admin/img/login-bg/img-03.jpeg') }});"
                data-paroller="true" data-paroller-type="foreground" data-paroller-factor="-0.25">
                <!-- Capa oscura para mejorar contraste -->
                <div class="bg-overlay"></div>
            </div>

            <div class="container home-content text-center">
                <span class="text-box p-2">
                    <h1 class="text-white">Sistema de Gestión de carnets</h1>
                    <h2 class="text-white">CIZEE</h2>

                    <p class="text-white fs-16px mt-3">
                        Plataforma oficial para la gestión de procesos de contratación del Gobierno Autónomo Municipal de El Alto<br>
                        conforme a la normativa vigente y principios de transparencia.
                    </p>

                    <div class="btn-group mt-3" role="group" aria-label="Acciones principales">
                        <a href="" class="btn btn-theme btn-primary btn-lg">Registrar Carnet</a>
                        <a href="#procesos" class="btn btn-theme btn-outline-white btn-lg ms-2">Ver Procesos</a>
                    </div>

                    <p class="text-white mt-4">¿Ya realizaste un registro? <a href="" class="text-warning">Consultar estado</a></p>
                </span>
            </div>
        </div>
        <!-- end #home -->

        <!-- begin #procesos -->
        <div id="procesos" class="content" data-scrollview="true">
            <div class="container" data-animation="true" data-animation-type="animate__fadeInDown">
                <h2 class="content-title text-center">Procesos de Contratación</h2>
                <p class="text-center">
                    Nuestro sistema garantiza procesos de contratación transparentes, eficientes y conforme a normativa.
                </p>

                <div class="row text-center mt-5">
                    <!-- Paso 1 -->
                    <div class="col-lg-4 mb-4">
                        <div class="about p-4 border rounded shadow-sm h-100">
                            <div class="step-number bg-theme text-white rounded-circle mx-auto mb-3"
                                style="width: 50px; height: 50px; line-height: 50px;">1</div>
                            <h3 class="mb-3 text-theme">Registro de Requerimiento</h3>
                            <p>
                                Las unidades solicitantes registran sus necesidades de contratación con todos los requisitos técnicos y especificaciones.
                            </p>
                        </div>
                    </div>

                    <!-- Paso 2 -->
                    <div class="col-lg-4 mb-4">
                        <div class="about p-4 border rounded shadow-sm h-100">
                            <div class="step-number bg-theme text-white rounded-circle mx-auto mb-3"
                                style="width: 50px; height: 50px; line-height: 50px;">2</div>
                            <h3 class="mb-3 text-theme">Evaluación y Aprobación</h3>
                            <p>
                                Nuestra unidad técnica evalúa la documentación y procede con la aprobación según disponibilidad presupuestaria.
                            </p>
                        </div>
                    </div>

                    <!-- Paso 3 -->
                    <div class="col-lg-4 mb-4">
                        <div class="about p-4 border rounded shadow-sm h-100">
                            <div class="step-number bg-theme text-white rounded-circle mx-auto mb-3"
                                style="width: 50px; height: 50px; line-height: 50px;">3</div>
                            <h3 class="mb-3 text-theme">Proceso Contractual</h3>
                            <p>
                                Según el monto y naturaleza, se realiza el proceso de contratación correspondiente (Licitación, Cotización, etc.).
                            </p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="#formulario" class="btn btn-theme btn-primary">Iniciar proceso</a>
                </div>
            </div>
        </div>
        <!-- end #procesos -->

        <!-- begin #requisitos -->
        <div id="requisitos" class="content has-bg bg-green" data-scrollview="true">
            <div class="content-bg"
                style="background-image: url({{ asset('pagina/assets/img/bg/bg-client-terminal.jpg') }})"
                data-paroller-factor="0.5" data-paroller-factor-md="0.01" data-paroller-factor-xs="0.01"></div>

            <div class="container" data-animation="true" data-animation-type="animate__fadeInUp">
                <span class="text-box p-2">
                    <h2 class="content-title text-white text-center">Requisitos para Contratación</h2>
                    <p class="text-white text-center mb-5">
                        Documentación necesaria según tipo de contratación.
                    </p>

                    <div class="row justify-content-center text-white">
                        <div class="col-md-10">
                            <div class="requisitos-list">
                                <div class="requisito-item">
                                    <i class="fa fa-check-circle me-2 text-light"></i>
                                    Requerimiento técnico debidamente fundamentado
                                </div>
                                <div class="requisito-item">
                                    <i class="fa fa-check-circle me-2 text-light"></i>
                                    Certificación presupuestaria
                                </div>
                                <div class="requisito-item">
                                    <i class="fa fa-check-circle me-2 text-light"></i>
                                    Planificación anual de contrataciones (según corresponda)
                                </div>
                                <div class="requisito-item">
                                    <i class="fa fa-check-circle me-2 text-light"></i>
                                    Estudio de mercado (para montos superiores a 20,000 Bs)
                                </div>
                                <div class="requisito-item">
                                    <i class="fa fa-check-circle me-2 text-light"></i>
                                    Términos de referencia o especificaciones técnicas
                                </div>
                                <div class="requisito-item">
                                    <i class="fa fa-check-circle me-2 text-light"></i>
                                    Documentación legal del proveedor (para contrataciones directas)
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="#formulario" class="btn btn-outline-light">Ver formatos</a>
                    </div>
                </span>
            </div>
        </div>
        <!-- end #requisitos -->

        <!-- begin #unidad-contratacion -->
        <div id="unidad-contratacion" class="content bg-black-darker has-bg" data-scrollview="true">
            <div class="content-bg" style="background-image: url({{ asset('pagina/assets/img/bg/bg-quote.jpg') }})"
                data-paroller-factor="0.5" data-paroller-factor-md="0.01" data-paroller-factor-xs="0.01"></div>

            <div class="container" data-animation="true" data-animation-type="animate__fadeInLeft">
                <span class="text-box p-2">
                    <div class="row">
                        <div class="col-lg-12 text-white text-center">
                            <h2 class="text-theme mb-3">CIZEE</h2>
                            <p class="lead">
                                Somos la unidad técnica responsable de gestionar los procesos de contratación de bienes, servicios y obras del GAMEA,
                                garantizando transparencia, eficiencia y cumplimiento normativo.
                            </p>
                            <p>
                                Nuestro equipo multidisciplinario está comprometido con la optimización de recursos públicos y la mejora continua
                                de los procesos de adquisiciones.
                            </p>
                            <small class="d-block mt-4">Trabajamos con transparencia, eficiencia y responsabilidad.</small>
                        </div>
                    </div>
                </span>
            </div>
        </div>
        <!-- end #unidad-contratacion -->

        <!-- begin #mision-vision -->
        <div id="mision-vision" class="content" data-scrollview="true">
            <div class="container">
                <h2 class="content-title">Misión y Visión</h2>
                <div class="row mb-5">
                    <div class="col-md-6" data-animation="true" data-animation-type="animate__fadeInLeft">
                        <div class="mission-card p-4 h-100">
                            <div class="icon-box bg-theme text-white rounded-circle mb-3 mx-auto"
                                style="width: 70px; height: 70px; line-height: 70px;">
                                <i class="fas fa-bullseye fa-2x"></i>
                            </div>
                            <h3 class="text-theme">Misión</h3>
                            <p>
                                Garantizar procesos de contratación transparentes, eficientes y conforme a normativa, optimizando el uso de recursos públicos
                                para satisfacer las necesidades institucionales con calidad y oportunidad.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6" data-animation="true" data-animation-type="animate__fadeInRight">
                        <div class="vision-card p-4 h-100">
                            <div class="icon-box bg-theme text-white rounded-circle mb-3 mx-auto"
                                style="width: 70px; height: 70px; line-height: 70px;">
                                <i class="fas fa-eye fa-2x"></i>
                            </div>
                            <h3 class="text-theme">Visión</h3>
                            <p>
                                Ser reconocidos como un modelo de gestión contractal eficiente y transparente, contribuyendo al desarrollo institucional
                                mediante procesos modernos, ágiles y con altos estándares de calidad.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #mision-vision -->

        <!-- begin #normativa -->
        <div id="normativa" class="content has-bg" data-scrollview="true">
            <div class="content-bg" style="background-image: url({{ asset('pagina/assets/img/bg/bg-action.jpg') }})"
                data-paroller-factor="0.5" data-paroller-factor-md="0.01" data-paroller-factor-xs="0.01"></div>

            <div class="container" data-animation="true" data-animation-type="animate__fadeInRight">
                <span class="text-box p-2">
                    <div class="row action-box align-items-center">
                        <div class="col-lg-9">
                            <div class="icon-large text-theme">
                                <i class="fa fa-gavel"></i>
                            </div>
                            <h3>Marco Normativo de Contrataciones</h3>
                            <p>
                                Nuestros procesos se rigen por la <strong>Ley N° 1178 de Administración y Control Gubernamentales</strong>,
                                <strong>Decreto Supremo N° 181</strong> y normativa municipal vigente.
                            </p>
                            <p>
                                Estas disposiciones garantizan transparencia, igualdad de condiciones y optimización de recursos en todas las contrataciones
                                que realiza el Gobierno Autónomo Municipal de El Alto.
                            </p>
                        </div>
                        <div class="col-lg-3">
                            <a href="https://www.lexivox.org/norms/BO-L-1178.xhtml" target="_blank"
                                rel="noopener noreferrer" class="btn btn-outline-white btn-theme btn-block">
                                Ver Ley 1178
                            </a>
                        </div>
                    </div>
                </span>
            </div>
        </div>
        <!-- end #normativa -->

        <!-- begin #consultar -->
        <div id="consultar" class="content has-bg bg-green" data-scrollview="true">
            <div class="content-bg" style="background-image: url({{ asset('pagina/assets/img/bg/bg-client.jpg') }})"
                data-paroller-factor="0.5" data-paroller-factor-md="0.01" data-paroller-factor-xs="0.01"></div>

            <div class="container" data-animation="true" data-animation-type="animate__fadeInUp">
                <h2 class="content-title text-center">Consultar estado de contratación</h2>
                <span class="text-box p-2">
                    <p class="text-center mb-5 text-light">
                        Ingrese el número de proceso o NIT/RUC para verificar el estado actual del trámite.
                    </p>
                </span>
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <form action="" method="GET" class="bg-white p-4 rounded shadow-sm">
                            <div class="mb-3">
                                <label for="numero_proceso" class="form-label">Número de Proceso o NIT/RUC</label>
                                <input type="text" class="form-control" id="numero_proceso"
                                    name="numero_proceso" placeholder="Ej: GAMEA-2023-001 / 123456789" required>
                            </div>
                            <button type="submit" class="btn btn-theme btn-primary w-100">
                                <i class="fa fa-search me-2"></i>Consultar
                            </button>
                        </form>
                    </div>
                </div>

                @if (session('resultado'))
                    <div class="row justify-content-center mt-4">
                        <div class="col-md-8 col-lg-6">
                            <div class="alert alert-info text-dark text-center">
                                {{ session('resultado') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- end #consultar -->

        <!-- begin #contacto -->
        <div id="contacto" class="content bg-light" data-scrollview="true">
            <div class="container">
                <h2 class="content-title">Contacto</h2>
                <p class="text-center">
                    Para consultas sobre procesos de contratación, proveedores o cualquier información relacionada.
                </p>

                <div class="row">
                    <div class="col-lg-6" data-animation="true" data-animation-type="animate__fadeInLeft">
                        <h3>Información de contacto</h3>
                        <p>
                            Nuestra oficina central atiende de lunes a viernes en horario de oficina.
                        </p>

                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt text-theme"></i>
                                <div>
                                    <strong>Oficina Central: JACHA UTHA</strong><br>
                                    Avenida Costanera, Nro: 5022 Urbanización Libertad, entre calle J.J. Torrez y calle
                                    Hernán Siles Zuaso (Jach´a Uta)
                                </div>
                            </div>
                        </div>

                        <div class="contact-methods mt-4">
                            <p>
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <a href="mailto:contrataciones.gam@elalto.gob.bo"
                                    class="text-dark">contrataciones.gam@elalto.gob.bo</a>
                            </p>
                            <p>
                                <i class="fas fa-phone-alt text-primary me-2"></i>
                                (2) 2837573 - Interno 245
                            </p>
                            <p>
                                <i class="fas fa-clock text-primary me-2"></i>
                                Horario de atención: Lunes a Viernes 8:30 - 16:30
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6" data-animation="true" data-animation-type="animate__fadeInRight">
                        <div class="p-4 bg-white rounded shadow-sm h-100">
                            <h3 class="mb-4">Formulario de contacto</h3>
                            <form>
                                <div class="mb-3">
                                    <input type="text" class="form-control" placeholder="Nombre completo" required>
                                </div>
                                <div class="mb-3">
                                    <input type="email" class="form-control" placeholder="Correo electrónico" required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" placeholder="Asunto">
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" rows="4" placeholder="Mensaje" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-theme btn-primary">Enviar mensaje</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #contacto -->

        <!-- begin #footer -->
        <div id="footer" class="footer">
            <div class="container">
                <div class="footer-brand">
                    <img src="{{ asset('pagina/assets/img/logos/logoBlanco.png') }}" alt="GAMEA"
                        style="height: 30px;">
                    <span class="ms-2">CIZEE</span>
                </div>
                <p>
                    &copy; Copyright {{ date('Y') }} - Todos los derechos reservados<br>
                    Dirección de Talento Humano - Secretaría Municipal de Administración y Finanzas
                </p>
                <p class="social-list">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f fa-fw"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram fa-fw"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter fa-fw"></i></a>
                </p>
            </div>
        </div>
        <!-- end #footer -->

        <!-- BEGIN theme-panel -->
        <div class="theme-panel">
            <a href="javascript:;" data-toggle="theme-panel-expand" class="theme-collapse-btn"
                aria-label="Configuración de tema"><i class="fa fa-cog"></i></a>
            <div class="theme-panel-content">
                <div class="theme-list clearfix">
                    <div class="theme-list-item"><a href="javascript:void(0);" class="bg-green"
                            data-theme-class="theme-green" data-toggle="theme-selector" data-bs-toggle="tooltip"
                            data-bs-trigger="hover" data-bs-container="body" data-bs-title="Green"
                            title=""></a></div>
                    <div class="theme-list-item"><a href="javascript:void(0);" class="bg-blue"
                            data-theme-class="theme-blue" data-toggle="theme-selector" data-bs-toggle="tooltip"
                            data-bs-trigger="hover" data-bs-container="body" data-bs-title="Blue"
                            title=""></a></div>
                    <div class="theme-list-item">
                        <a href="javascript:void(0);" class="bg-gradient-institucional"
                            data-theme-class="theme-institucional" data-toggle="theme-selector"
                            data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body"
                            data-bs-title="Institucional" title="">
                        </a>
                    </div>
                </div>
                <hr class="mb-0" />
                <div class="row mt-10px pt-3px">
                    <div class="col-9 control-label fw-bold">
                        <div>Modo oscuro </div>
                        <div class="lh-14 fs-13px">
                            <small class="">
                                Ajuste la apariencia para reducir el resplandor y darle un descanso a sus ojos.
                            </small>
                        </div>
                    </div>
                    <div class="col-3 d-flex">
                        <div class="form-check form-switch ms-auto mb-0 mt-2px">
                            <input type="checkbox" class="form-check-input" id="appThemeDarkMode"
                                name="app-theme-dark-mode" value="1">
                            <label class="form-check-label" for="appThemeDarkMode">
                                <span class="visually-hidden">Modo oscuro</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END theme-panel -->
    </div>
    <!-- end #page-container -->

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="{{ asset('pagina/assets/js/one-page-parallax/vendor.min.js') }}"></script>
    <script src="{{ asset('pagina/assets/js/one-page-parallax/app.min.js') }}"></script>

    <script>
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 50) {
                header.classList.add('scroll-active');
            } else {
                header.classList.remove('scroll-active');
            }
        });
    </script>
</body>

</html>