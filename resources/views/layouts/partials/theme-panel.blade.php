<!-- BEGIN panel-tema -->
<div class="theme-panel">
    <a href="javascript:;" data-toggle="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-cog"></i></a>
    <div class="theme-panel-content" data-scrollbar="true" data-height="100%">
        <h5>Configuración de la App</h5>
        
        <!-- BEGIN lista-temas -->
        <div class="theme-list">
            <div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-red" data-theme-class="theme-red" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Rojo">&nbsp;</a></div>
            <div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-green" data-theme-class="theme-green" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Verde">&nbsp;</a></div>
            <div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-blue" data-theme-class="theme-blue" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Azul">&nbsp;</a></div>
            <div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-black" data-theme-class="theme-gray-600" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Negro">&nbsp;</a></div>
        </div>
        <!-- END lista-temas -->
        
        <div class="theme-panel-divider"></div>
        
        <div class="row mt-10px">
            <div class="col-8 control-label text-body fw-bold">
                <div>Modo Oscuro </div>
                <div class="lh-14">
                    <small class="text-body opacity-50">
                        Ajusta la apariencia para reducir el brillo y darle un descanso a tus ojos.
                    </small>
                </div>
            </div>
            <div class="col-4 d-flex">
                <div class="form-check form-switch ms-auto mb-0">
                    <input type="checkbox" class="form-check-input" name="app-theme-dark-mode" id="appThemeDarkMode" value="1" />
                    <label class="form-check-label" for="appThemeDarkMode">&nbsp;</label>
                </div>
            </div>
        </div>
        
        <div class="theme-panel-divider"></div>
        
        <!-- BEGIN interruptor-tema -->
        <div class="row mt-10px align-items-center">
            <div class="col-8 control-label text-body fw-bold">Encabezado Fijo</div>
            <div class="col-4 d-flex">
                <div class="form-check form-switch ms-auto mb-0">
                    <input type="checkbox" class="form-check-input" name="app-header-fixed" id="appHeaderFixed" value="1" checked />
                    <label class="form-check-label" for="appHeaderFixed">&nbsp;</label>
                </div>
            </div>
        </div>
        <div class="row mt-10px align-items-center">
            <div class="col-8 control-label text-body fw-bold">Encabezado Inverso</div>
            <div class="col-4 d-flex">
                <div class="form-check form-switch ms-auto mb-0">
                    <input type="checkbox" class="form-check-input" name="app-header-inverse" id="appHeaderInverse" value="1" />
                    <label class="form-check-label" for="appHeaderInverse">&nbsp;</label>
                </div>
            </div>
        </div>
        <div class="row mt-10px align-items-center">
            <div class="col-8 control-label text-body fw-bold">Barra Lateral Fija</div>
            <div class="col-4 d-flex">
                <div class="form-check form-switch ms-auto mb-0">
                    <input type="checkbox" class="form-check-input" name="app-sidebar-fixed" id="appSidebarFixed" value="1" checked />
                    <label class="form-check-label" for="appSidebarFixed">&nbsp;</label>
                </div>
            </div>
        </div>
        <div class="row mt-10px align-items-center">
            <div class="col-8 control-label text-body fw-bold">Cuadrícula en Barra Lateral</div>
            <div class="col-4 d-flex">
                <div class="form-check form-switch ms-auto mb-0">
                    <input type="checkbox" class="form-check-input" name="app-sidebar-grid" id="appSidebarGrid" value="1" />
                    <label class="form-check-label" for="appSidebarGrid">&nbsp;</label>
                </div>
            </div>
        </div>
        <div class="row mt-10px align-items-center">
            <div class="col-8 control-label text-body fw-bold">Gradiente Habilitado</div>
            <div class="col-4 d-flex">
                <div class="form-check form-switch ms-auto mb-0">
                    <input type="checkbox" class="form-check-input" name="app-gradient-enabled" id="appGradientEnabled" value="1" />
                    <label class="form-check-label" for="appGradientEnabled">&nbsp;</label>
                </div>
            </div>
        </div>
        <!-- END interruptor-tema -->
        <br>
        <a href="javascript:;" class="btn btn-default d-block w-100 rounded-pill" data-toggle="reset-local-storage"><b>Restablecer configuraciones</b></a>
    </div>
</div>
<!-- END panel-tema -->
<!-- BEGIN boton-desplazar-superior -->
<a href="javascript:;" class="btn btn-icon btn-circle btn-theme btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
<!-- END boton-desplazar-superior -->