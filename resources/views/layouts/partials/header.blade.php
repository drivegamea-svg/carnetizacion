 <!-- BEGIN #header -->
 <div id="header" class="app-header">
     <!-- BEGIN navbar-header -->
     <div class="navbar-header">
         <a href="{{route('dashboard')}}" class="navbar-brand"><img  src="{{ asset('pagina/assets/img/logos/logoCizee.png') }}"
                 alt="logo gamea"> &nbsp;&nbsp;&nbsp;  SISTEMA DE CARNETIZACION<b class="me-3px">&nbsp; </b> </a>
         <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
         </button>
     </div>
     <!-- END navbar-header -->
     <!-- BEGIN header-nav -->
     <div class="navbar-nav">
         {{-- <div class="navbar-item navbar-form">
                <form action="" method="POST" name="search">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Enter keyword" />
                        <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div> --}}
         {{-- <div class="navbar-item dropdown">
                <a href="#" data-bs-toggle="dropdown" class="navbar-link dropdown-toggle icon">
                    <i class="fa fa-bell"></i>
                    <span class="badge">5</span>
                </a>
                <div class="dropdown-menu media-list dropdown-menu-end">
                    <div class="dropdown-header">NOTIFICATIONS (5)</div>
                    <a href="javascript:;" class="dropdown-item media">
                        <div class="media-left">
                            <i class="fa fa-bug media-object bg-gray-500"></i>
                        </div>
                        <div class="media-body">
                            <h6 class="media-heading">Server Error Reports <i class="fa fa-exclamation-circle text-danger"></i></h6>
                            <div class="text-muted fs-10px">3 minutes ago</div>
                        </div>
                    </a>
                    <a href="javascript:;" class="dropdown-item media">
                        <div class="media-left">
                            <img src="{{ asset('color-admin/img/user/user-1.jpg') }}" class="media-object" alt="" />
                            <i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
                        </div>
                        <div class="media-body">
                            <h6 class="media-heading">John Smith</h6>
                            <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                            <div class="text-muted fs-10px">25 minutes ago</div>
                        </div>
                    </a>
                    <a href="javascript:;" class="dropdown-item media">
                        <div class="media-left">
                            <img src="{{ asset('color-admin/img/user/user-2.jpg') }}" class="media-object" alt="" />
                            <i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
                        </div>
                        <div class="media-body">
                            <h6 class="media-heading">Olivia</h6>
                            <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                            <div class="text-muted fs-10px">35 minutes ago</div>
                        </div>
                    </a>
                    <a href="javascript:;" class="dropdown-item media">
                        <div class="media-left">
                            <i class="fa fa-plus media-object bg-gray-500"></i>
                        </div>
                        <div class="media-body">
                            <h6 class="media-heading"> New User Registered</h6>
                            <div class="text-muted fs-10px">1 hour ago</div>
                        </div>
                    </a>
                    <a href="javascript:;" class="dropdown-item media">
                        <div class="media-left">
                            <i class="fa fa-envelope media-object bg-gray-500"></i>
                            <i class="fab fa-google text-warning media-object-icon fs-14px"></i>
                        </div>
                        <div class="media-body">
                            <h6 class="media-heading"> New Email From John</h6>
                            <div class="text-muted fs-10px">2 hour ago</div>
                        </div>
                    </a>
                    <div class="dropdown-footer text-center">
                        <a href="javascript:;" class="text-decoration-none">View more</a>
                    </div>
                </div>
            </div>
             --}}
         <div class="navbar-item navbar-user dropdown">
             <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                 <!-- Foto de perfil dinámica -->
                 @if (auth()->user()->profile_photo_path)
                     <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                         alt="{{ auth()->user()->name }}" class="rounded-circle"
                         style="width: 32px; height: 32px; object-fit: cover" />
                 @else
                     <img src="{{ asset('color-admin/img/user/user-13.jpg') }}" alt="{{ auth()->user()->name }}"
                         class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover" />
                 @endif

                 <span>
                     <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                     <b class="caret"></b>
                 </span>
             </a>

             <div class="dropdown-menu dropdown-menu-end me-1">
                 <!-- Enlace a perfil -->
                 <a href="{{ route('profile.edit') }}" class="dropdown-item">
                     <i class="fas fa-user-edit me-2"></i> Editar Perfil
                 </a>

                 <!-- Opción de inbox con badge -->
                 {{-- <a href="#" class="dropdown-item d-flex align-items-center">
                    <i class="fas fa-inbox me-2"></i> Notificaciones
                    @if ($unreadNotifications = auth()->user()->unreadNotifications->count())
                    <span class="badge bg-danger rounded-pill ms-auto">{{ $unreadNotifications }}</span>
                    @endif
                    </a> --}}

                 <!-- Divider -->
                 <div class="dropdown-divider"></div>

                 <!-- Menú para administradores (usando Spatie) -->
                 @if (auth()->user()->hasAnyRole(['admin', 'super-admin']))
                     <h6 class="dropdown-header">Administración</h6>
                     <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                         <i class="fas fa-cog me-2"></i> Panel Admin
                     </a>
                 @endif

                 <!-- Cerrar sesión -->
                 <form method="POST" action="{{ route('logout') }}">
                     @csrf
                     <a href="#" class="dropdown-item"
                         onclick="event.preventDefault(); this.closest('form').submit();">
                         <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                     </a>
                 </form>
             </div>
         </div>
     </div>
     <!-- END header-nav -->
 </div>
 <!-- END #header -->
