  <!-- BEGIN #loader -->
  <div id="loader" class="app-loader">
      <span class="spinner"></span>
  </div>
  <!-- END #loader -->

  <!-- BEGIN #sidebar -->
  <div id="sidebar" class="app-sidebar" data-bs-theme="dark">
      <!-- BEGIN scrollbar -->
      <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
          <!-- BEGIN menu -->
          <div class="menu">
              <!-- Perfil -->
              <div class="menu-profile">
                  <a href="javascript:;" class="menu-profile-link" data-toggle="app-sidebar-profile"
                      data-target="#appSidebarProfileMenu">
                      <div class="menu-profile-cover with-shadow"></div>
                      <div class="menu-profile-image">
                          @if (auth()->user()->profile_photo_path)
                              <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                                  alt="Foto de perfil" />
                          @else
                              <img src="{{ asset('color-admin/img/user/user-13.jpg') }}" alt="Usuario sin foto" />
                          @endif
                      </div>
                      <div class="menu-profile-info">
                          <div class="d-flex align-items-center">
                              <div class="flex-grow-1">{{ auth()->user()->name }}</div>
                              <div class="menu-caret ms-auto"></div>
                          </div>
                          <small>
                              @foreach (auth()->user()->roles as $role)
                                  {{ $role->name }}
                                  @if (!$loop->last)
                                      ,
                                  @endif
                              @endforeach
                          </small>
                      </div>
                  </a>
              </div>

              <div id="appSidebarProfileMenu" class="collapse">
                  <div class="menu-item pt-5px">
                      <a href="javascript:;" class="menu-link">
                          <div class="menu-icon"><i class="fa fa-cog"></i></div>
                          <div class="menu-text">Configuraciones</div>
                      </a>
                  </div>
                  <div class="menu-item pb-5px">
                      <a href="javascript:;" class="menu-link">
                          <div class="menu-icon"><i class="fa fa-question-circle"></i></div>
                          <div class="menu-text">Ayuda</div>
                      </a>
                  </div>
              </div>

              <!-- Panel principal -->
              <div class="menu-header">Panel de Control</div>

              <div class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                  <a href="{{ route('dashboard') }}" class="menu-link">
                      <div class="menu-icon"><i class="fa fa-tachometer-alt"></i></div>
                      <div class="menu-text">Inicio</div>
                  </a>
              </div>

              <!-- Gestión de permisos individuales -->
              @can('ver afiliados')
                  <div class="menu-item {{ request()->routeIs('afiliados.*') ? 'active' : '' }}">
                      <a href="{{ route('afiliados.index') }}" class="menu-link">
                          <div class="menu-icon"><i class="fas fa-users"></i></div>
                          <div class="menu-text">Afiliados </div>
                      </a>
                  </div>
              @endcan


              @canany(['ver reportes afiliados', 'ver reportes estadisticos', 'ver reportes sectores'])
                  <div class="menu-item {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                      <a href="{{ route('reportes.index') }}" class="menu-link">
                          <div class="menu-icon"><i class="fa fa-file-alt"></i></div>
                          <div class="menu-text">Reportes</div>
                      </a>
                  </div>
              @endcanany




              <div class="menu-header">Ajustes Generales</div>
              <!-- Gestión de usuarios -->
              <div
                  class="menu-item has-sub {{ request()->routeIs(['admin.usuarios.*', 'admin.roles.*']) ? 'active' : '' }}">
                  <a href="javascript:;" class="menu-link">
                      <div class="menu-icon"><i class="fa fa-users"></i></div>
                      <div class="menu-text">Gestión de Usuarios</div>
                      <div class="menu-caret"></div>
                  </a>
                  <div class="menu-submenu">
                      <div class="menu-item {{ request()->routeIs('admin.usuarios.index') ? 'active' : '' }}">
                          <a href="{{ route('admin.usuarios.index') }}" class="menu-link">
                              <div class="menu-text">Usuarios</div>
                          </a>
                      </div>
                      <div class="menu-item {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}">
                          <a href="{{ route('admin.roles.index') }}" class="menu-link">
                              <div class="menu-text">Roles y Permisos</div>
                          </a>
                      </div>
                  </div>
              </div>

              <!-- Configuraciones -->
              <div
                  class="menu-item has-sub {{ request()->routeIs(['puntos-atencion.*', 'medios-transporte.*', 'zonas.*', 'motivos-viaje.*', 'tipos-autorizacion.*', 'destinos-viaje.*', 'datos-administrativos.*']) ? 'active' : '' }}">
                  <a href="javascript:;" class="menu-link">
                      <div class="menu-icon"><i class="fa fa-cogs"></i></div>
                      <div class="menu-text">Configuraciones del Sistema</div>
                      <div class="menu-caret"></div>
                  </a>
                  <div class="menu-submenu">

                      @can('ver destinos viaje')
                          <div class="menu-item {{ request()->routeIs('destinos-viaje.*') ? 'active' : '' }}">
                              <a href="{{ route('destinos-viaje.index') }}" class="menu-link"
                                  style="padding-left: 1.75rem;">
                                  <i class="fas fa-map-marker-alt align-self-center"
                                      style="position: absolute; left: 0.75rem;"></i>
                                  <div class="menu-text">Destinos de Viaje</div>
                              </a>
                          </div>
                      @endcan


                   

                      @can('ver organizaciones sociales')
                          <div class="menu-item {{ request()->routeIs('organizaciones-sociales.*') ? 'active' : '' }}">
                              <a href="{{ route('organizaciones-sociales.index') }}" class="menu-link"
                                  style="padding-left: 1.75rem;">
                                  <i class="fas fa-hands-helping align-self-center"
                                      style="position: absolute; left: 0.75rem;"></i>
                                  <div class="menu-text">Organizaciones Sociales</div>
                              </a>
                          </div>
                      @endcan

                      @can('ver sectores economicos')
                          <div class="menu-item {{ request()->routeIs('sectores-economicos.*') ? 'active' : '' }}">
                              <a href="{{ route('sectores-economicos.index') }}" class="menu-link"
                                  style="padding-left: 1.75rem;">
                                  <i class="fas fa-chart-pie align-self-center"
                                      style="position: absolute; left: 0.75rem;"></i>
                                  <div class="menu-text">Sectores Económicos</div>
                              </a>
                          </div>
                      @endcan

                      @can('ver profesiones tecnicas')
                          <div class="menu-item {{ request()->routeIs('profesiones-tecnicas.*') ? 'active' : '' }}">
                              <a href="{{ route('profesiones-tecnicas.index') }}" class="menu-link"
                                  style="padding-left: 1.75rem;">
                                  <i class="fas fa-tools align-self-center"
                                      style="position: absolute; left: 0.75rem;"></i>
                                  <div class="menu-text">Profesiones Técnicas</div>
                              </a>
                          </div>
                      @endcan

                      @can('ver especialidades')
                          <div class="menu-item {{ request()->routeIs('especialidades.*') ? 'active' : '' }}">
                              <a href="{{ route('especialidades.index') }}" class="menu-link"
                                  style="padding-left: 1.75rem;">
                                  <i class="fas fa-certificate align-self-center"
                                      style="position: absolute; left: 0.75rem;"></i>
                                  <div class="menu-text">Especialidades</div>
                              </a>
                          </div>
                      @endcan


                      @can('ver ciudades')
                          <div class="menu-item {{ request()->routeIs('ciudades.*') ? 'active' : '' }}">
                              <a href="{{ route('ciudades.index') }}" class="menu-link" style="padding-left: 1.75rem;">
                                  <i class="fas fa-city align-self-center" style="position: absolute; left: 0.75rem;"></i>
                                  <div class="menu-text">Ciudades</div>
                              </a>
                          </div>
                      @endcan

                  </div>
              </div>

              <!-- Minificar menú -->
              <div class="menu-item d-flex">
                  <a href="javascript:;"
                      class="app-sidebar-minify-btn ms-auto d-flex align-items-center text-decoration-none"
                      data-toggle="app-sidebar-minify">
                      <i class="fa fa-angle-double-left"></i>
                  </a>
              </div>
          </div>


          <!-- END menu -->
      </div>
      <!-- END scrollbar -->
  </div>
  <div class="app-sidebar-bg" data-bs-theme="dark"></div>
  <div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile"
          class="stretched-link"></a></div>
  <!-- END #sidebar -->
