<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CarnetController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\AfiliadoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TipoEmpresaController;
use App\Http\Controllers\OrganizacionSocialController;
use App\Http\Controllers\SectorEconomicoController;
use App\Http\Controllers\ProfesionTecnicaController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\CiudadController;

// Ruta principal
Route::get('/', function () {
    return redirect()->route('login');
});


// RUTAS PÚBLICAS
Route::get('/verificar-afiliado/{id}', [AfiliadoController::class, 'showPublic'])
    ->name('afiliados.show.public');


    // RUTAS DE AUTENTICACIÓN BÁSICA
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// RUTAS DE ADMINISTRACIÓN
Route::middleware(['auth', IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Roles
        Route::resource('roles', RoleController::class)->names('roles');
        Route::post('roles/{role}/restore', [RoleController::class, 'restore'])->name('roles.restore');
        Route::delete('roles/{role}/force-delete', [RoleController::class, 'forceDelete'])->name('roles.forceDelete');

        // Usuarios
        Route::resource('usuarios', UsuarioController::class)->names('usuarios');
    });


// RUTAS PARA AFILIADOS CON PERMISOS ESPECÍFICOS
Route::middleware(['auth'])->group(function () {
    // Crear afiliados
    Route::middleware(['permission:crear afiliados'])->group(function () {
        Route::get('/afiliados/create', [AfiliadoController::class, 'create'])
            ->name('afiliados.create');
        Route::post('/afiliados', [AfiliadoController::class, 'store'])
            ->name('afiliados.store');
    });

    // Ver afiliados (index y show)
    Route::middleware(['permission:ver afiliados'])->group(function () {
        Route::get('/afiliados', [AfiliadoController::class, 'index'])
            ->name('afiliados.index');
        Route::get('/afiliados/{id}', [AfiliadoController::class, 'show'])
            ->name('afiliados.show');
    });

    // Editar afiliados
    Route::middleware(['permission:editar afiliados'])->group(function () {
        Route::get('/afiliados/{id}/edit', [AfiliadoController::class, 'edit'])
            ->name('afiliados.edit');
        Route::put('/afiliados/{id}', [AfiliadoController::class, 'update'])
            ->name('afiliados.update');
    });

    // Eliminar/Restaurar afiliados - CORREGIDO
    Route::middleware(['permission:eliminar afiliados'])->group(function () {
        Route::delete('/afiliados/{id}', [AfiliadoController::class, 'destroy'])
            ->name('afiliados.destroy');
        Route::post('/afiliados/{id}/restaurar', [AfiliadoController::class, 'restaurar'])
            ->name('afiliados.restaurar'); // Cambié de 'restore' a 'restaurar'
        Route::post('/afiliados/{id}/restaurar-desde-create', [AfiliadoController::class, 'restaurarDesdeCreate'])
        ->name('afiliados.restaurar.desde.create');
    });

    // Activar/Desactivar afiliados
    Route::middleware(['permission:activar afiliados'])->group(function () {
        Route::post('/afiliados/{id}/activar', [AfiliadoController::class, 'activar'])
            ->name('afiliados.activar');
        Route::post('/afiliados/{id}/desactivar', [AfiliadoController::class, 'desactivar'])
            ->name('afiliados.desactivar');
    });

    Route::prefix('afiliados/{afiliado}/archivos')->name('afiliados.archivos.')->group(function () {
        Route::post('/', [AfiliadoController::class, 'storeArchivo'])->name('store');
        Route::delete('/{archivo}', [AfiliadoController::class, 'destroyArchivo'])->name('destroy');
    });

   
    Route::get('/buscar-persona/{ci}', [AfiliadoController::class, 'buscarPorCi'])
        ->name('afiliados.buscar');
});

// RUTAS PARA REPORTES
Route::middleware(['auth'])->group(function () {
    // Página principal de reportes - Accesible si tiene al menos un permiso de ver
    Route::get('/reportes', [ReporteController::class, 'index'])
        ->name('reportes.index')
        ->middleware('permission:ver reportes afiliados|ver reportes estadisticos|ver reportes sectores');

    // Reportes de afiliados
    Route::middleware(['permission:ver reportes afiliados'])->group(function () {
        Route::get('/reportes/afiliados', [ReporteController::class, 'reporteAfiliados'])
            ->name('reportes.afiliados');
        Route::post('/reportes/afiliados/generar', [ReporteController::class, 'generarReporteAfiliados'])
            ->name('reportes.afiliados.generar');
    });

    // Reportes estadísticos
    Route::middleware(['permission:ver reportes estadisticos'])->group(function () {
        Route::get('/reportes/estadisticas', [ReporteController::class, 'reporteEstadisticas'])
            ->name('reportes.estadisticas');
    });

    // Reportes por sectores
    Route::middleware(['permission:ver reportes sectores'])->group(function () {
        Route::get('/reportes/sectores', [ReporteController::class, 'reporteSectores'])
            ->name('reportes.sectores');
    });

    // Exportación de reportes - Permisos específicos
    Route::middleware(['permission:exportar excel estadisticos|exportar pdf estadisticos'])->group(function () {
        Route::post('/reportes/exportar/estadisticas', [ReporteController::class, 'exportarEstadisticas'])
            ->name('reportes.exportar.estadisticas');
    });

    Route::middleware(['permission:exportar excel sectores|exportar pdf sectores'])->group(function () {
        Route::post('/reportes/exportar/sectores', [ReporteController::class, 'exportarSectores'])
            ->name('reportes.exportar.sectores');
    });
});


// Agregar estas rutas junto a la existente
Route::middleware(['permission:imprimir carnets afiliados'])->group(function () {
    Route::get('/afiliados/{id}/carnet-pdf', [CarnetController::class, 'carnetPdf'])
        ->name('afiliados.carnet.pdf');
    
    // Nuevas rutas para carnets masivos
    Route::post('/afiliados/carnets-masivos', [CarnetController::class, 'carnetsMasivos'])
        ->name('afiliados.carnets.masivos');
    
    Route::get('/afiliados/carnets-todos', [CarnetController::class, 'carnetsTodosActivos'])
        ->name('afiliados.carnets.todos');
    
    Route::get('/afiliados/buscar', [AfiliadoController::class, 'buscarAfiliados'])
        ->name('afiliados.buscar');
});


// routes/web.php

Route::middleware(['auth'])->group(function () {
    // Crear tipos empresa
    Route::middleware(['permission:crear tipos empresa'])->group(function () {
        Route::get('/tipos-empresa/create', [TipoEmpresaController::class, 'create'])
            ->name('tipos-empresa.create');
        Route::post('/tipos-empresa', [TipoEmpresaController::class, 'store'])
            ->name('tipos-empresa.store');
    });

    // Ver tipos empresa
    Route::middleware(['permission:ver tipos empresa'])->group(function () {
        Route::get('/tipos-empresa', [TipoEmpresaController::class, 'index'])
            ->name('tipos-empresa.index');
    });

    // Editar tipos empresa
    Route::middleware(['permission:editar tipos empresa'])->group(function () {
        Route::get('/tipos-empresa/{tipoEmpresa}/edit', [TipoEmpresaController::class, 'edit'])
            ->name('tipos-empresa.edit');
        Route::put('/tipos-empresa/{tipoEmpresa}', [TipoEmpresaController::class, 'update'])
            ->name('tipos-empresa.update');
    });

    // Eliminar tipos empresa
    Route::middleware(['permission:eliminar tipos empresa'])->group(function () {
        Route::delete('/tipos-empresa/{tipoEmpresa}', [TipoEmpresaController::class, 'destroy'])
            ->name('tipos-empresa.destroy');
    });
});


Route::middleware(['auth'])->group(function () {
    // Crear organizaciones sociales
    Route::middleware(['permission:crear organizaciones sociales'])->group(function () {
        Route::get('/organizaciones-sociales/create', [OrganizacionSocialController::class, 'create'])
            ->name('organizaciones-sociales.create');
        Route::post('/organizaciones-sociales', [OrganizacionSocialController::class, 'store'])
            ->name('organizaciones-sociales.store');
    });

    // Ver organizaciones sociales
    Route::middleware(['permission:ver organizaciones sociales'])->group(function () {
        Route::get('/organizaciones-sociales', [OrganizacionSocialController::class, 'index'])
            ->name('organizaciones-sociales.index');
    });

    // Editar organizaciones sociales
    Route::middleware(['permission:editar organizaciones sociales'])->group(function () {
        Route::get('/organizaciones-sociales/{organizacionSocial}/edit', [OrganizacionSocialController::class, 'edit'])
            ->name('organizaciones-sociales.edit');
        Route::put('/organizaciones-sociales/{organizacionSocial}', [OrganizacionSocialController::class, 'update'])
            ->name('organizaciones-sociales.update');
    });

    // Eliminar organizaciones sociales
    Route::middleware(['permission:eliminar organizaciones sociales'])->group(function () {
        Route::delete('/organizaciones-sociales/{organizacionSocial}', [OrganizacionSocialController::class, 'destroy'])
            ->name('organizaciones-sociales.destroy');
    });
});


Route::middleware(['auth'])->group(function () {
    // Crear sectores económicos
    Route::middleware(['permission:crear sectores economicos'])->group(function () {
        Route::get('/sectores-economicos/create', [SectorEconomicoController::class, 'create'])
            ->name('sectores-economicos.create');
        Route::post('/sectores-economicos', [SectorEconomicoController::class, 'store'])
            ->name('sectores-economicos.store');
    });

    // Ver sectores económicos
    Route::middleware(['permission:ver sectores economicos'])->group(function () {
        Route::get('/sectores-economicos', [SectorEconomicoController::class, 'index'])
            ->name('sectores-economicos.index');
    });

    // Editar sectores económicos
    Route::middleware(['permission:editar sectores economicos'])->group(function () {
        Route::get('/sectores-economicos/{sectorEconomico}/edit', [SectorEconomicoController::class, 'edit'])
            ->name('sectores-economicos.edit');
        Route::put('/sectores-economicos/{sectorEconomico}', [SectorEconomicoController::class, 'update'])
            ->name('sectores-economicos.update');
    });

    // Eliminar sectores económicos
    Route::middleware(['permission:eliminar sectores economicos'])->group(function () {
        Route::delete('/sectores-economicos/{sectorEconomico}', [SectorEconomicoController::class, 'destroy'])
            ->name('sectores-economicos.destroy');
    });
});

Route::middleware(['auth'])->group(function () {
    // Crear profesiones técnicas
    Route::middleware(['permission:crear profesiones tecnicas'])->group(function () {
        Route::get('/profesiones-tecnicas/create', [ProfesionTecnicaController::class, 'create'])
            ->name('profesiones-tecnicas.create');
        Route::post('/profesiones-tecnicas', [ProfesionTecnicaController::class, 'store'])
            ->name('profesiones-tecnicas.store');
    });

    // Ver profesiones técnicas
    Route::middleware(['permission:ver profesiones tecnicas'])->group(function () {
        Route::get('/profesiones-tecnicas', [ProfesionTecnicaController::class, 'index'])
            ->name('profesiones-tecnicas.index');
    });

    // Editar profesiones técnicas
    Route::middleware(['permission:editar profesiones tecnicas'])->group(function () {
        Route::get('/profesiones-tecnicas/{profesionTecnica}/edit', [ProfesionTecnicaController::class, 'edit'])
            ->name('profesiones-tecnicas.edit');
        Route::put('/profesiones-tecnicas/{profesionTecnica}', [ProfesionTecnicaController::class, 'update'])
            ->name('profesiones-tecnicas.update');
    });

    // Eliminar profesiones técnicas
    Route::middleware(['permission:eliminar profesiones tecnicas'])->group(function () {
        Route::delete('/profesiones-tecnicas/{profesionTecnica}', [ProfesionTecnicaController::class, 'destroy'])
            ->name('profesiones-tecnicas.destroy');
    });
});


// Agregar esto en routes/web.php
Route::middleware(['auth'])->group(function () {
    // Crear especialidades
    Route::middleware(['permission:crear especialidades'])->group(function () {
        Route::get('/especialidades/create', [EspecialidadController::class, 'create'])
            ->name('especialidades.create');
        Route::post('/especialidades', [EspecialidadController::class, 'store'])
            ->name('especialidades.store');
    });

    // Ver especialidades
    Route::middleware(['permission:ver especialidades'])->group(function () {
        Route::get('/especialidades', [EspecialidadController::class, 'index'])
            ->name('especialidades.index');
    });

    // Editar especialidades
    Route::middleware(['permission:editar especialidades'])->group(function () {
        Route::get('/especialidades/{especialidad}/edit', [EspecialidadController::class, 'edit'])
            ->name('especialidades.edit');
        Route::put('/especialidades/{especialidad}', [EspecialidadController::class, 'update'])
            ->name('especialidades.update');
    });

    // Eliminar especialidades
    Route::middleware(['permission:eliminar especialidades'])->group(function () {
        Route::delete('/especialidades/{especialidad}', [EspecialidadController::class, 'destroy'])
            ->name('especialidades.destroy');
    });
});


// Agregar esto en routes/web.php
Route::middleware(['auth'])->group(function () {
    // Crear ciudades
    Route::middleware(['permission:crear ciudades'])->group(function () {
        Route::get('/ciudades/create', [CiudadController::class, 'create'])
            ->name('ciudades.create');
        Route::post('/ciudades', [CiudadController::class, 'store'])
            ->name('ciudades.store');
    });

    // Ver ciudades
    Route::middleware(['permission:ver ciudades'])->group(function () {
        Route::get('/ciudades', [CiudadController::class, 'index'])
            ->name('ciudades.index');
    });

    // Editar ciudades
    Route::middleware(['permission:editar ciudades'])->group(function () {
        Route::get('/ciudades/{ciudad}/edit', [CiudadController::class, 'edit'])
            ->name('ciudades.edit');
        Route::put('/ciudades/{ciudad}', [CiudadController::class, 'update'])
            ->name('ciudades.update');
    });

    // Eliminar ciudades
    Route::middleware(['permission:eliminar ciudades'])->group(function () {
        Route::delete('/ciudades/{ciudad}', [CiudadController::class, 'destroy'])
            ->name('ciudades.destroy');
    });
});





// Carga las rutas de autenticación (login, register, etc.)
require __DIR__ . '/auth.php';
// require __DIR__.'/breadcrumbs.php';