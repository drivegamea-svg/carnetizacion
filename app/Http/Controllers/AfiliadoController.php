<?php
// app/Http/Controllers/AfiliadoController.php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use App\Models\ArchivoAfiliado; // <-- Agrega esta línea

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

use App\Http\Requests\StoreAfiliadoRequest;
use App\Http\Requests\UpdateAfiliadoRequest;

use App\Models\Ciudad;
use App\Models\ProfesionTecnica;
use App\Models\Especialidad;
use App\Models\SectorEconomico;
use App\Models\OrganizacionSocial;

class AfiliadoController extends Controller
{
   
public function index(Request $request)
{
    if ($request->ajax()) {
        $afiliados = Afiliado::with([
            'ciudad', 
            'profesionTecnica', 
            'especialidad', 
            'sectorEconomico',
            'organizacionSocial'
        ])->withTrashed();
        
        $afiliados->orderBy('fecha_afiliacion', 'desc');

        return DataTables::of($afiliados)
            ->addColumn('actions', function ($afiliado) {
                $actions = '<div class="action-group">';
                
                // Botón VER
                if (auth()->user()->can('ver afiliados')) {
                    $actions .= '<a href="' . route('afiliados.show', $afiliado->id) . '" class="btn btn-outline-info btn-sm" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                    </a>';
                }

                // Botón EDITAR
                if (auth()->user()->can('editar afiliados') && !$afiliado->trashed()) {
                    $actions .= '<a href="' . route('afiliados.edit', $afiliado->id) . '" class="btn btn-outline-warning btn-sm" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>';
                }

                // Botón ELIMINAR/RESTAURAR
                if (auth()->user()->can('eliminar afiliados')) {
                    if ($afiliado->trashed()) {
                        $actions .= '
                        <form id="restore-form-' . $afiliado->id . '" action="' . route('afiliados.restaurar', $afiliado->id) . '" method="POST" style="display:inline-block">
                            ' . csrf_field() . '
                            <button type="button" class="btn btn-sm btn-outline-warning" title="Restaurar" onclick="confirmRestore(\'' . $afiliado->id . '\')">
                                <i class="fas fa-undo"></i>
                            </button>
                        </form>';
                    } else {
                        $actions .= '
                        <form id="delete-form-' . $afiliado->id . '" action="' . route('afiliados.destroy', $afiliado->id) . '" method="POST" style="display:inline-block">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete(\'' . $afiliado->id . '\')" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>';
                    }
                }

                // Botones ACTIVAR/DESACTIVAR
                if (auth()->user()->can('activar afiliados') && !$afiliado->trashed()) {
                    if ($afiliado->estado === 'PENDIENTE' || $afiliado->estado === 'INACTIVO') {
                        $actions .= '
                        <form id="activate-form-' . $afiliado->id . '" action="' . route('afiliados.activar', $afiliado->id) . '" method="POST" style="display:inline-block">
                            ' . csrf_field() . '
                            <button type="button" class="btn btn-sm btn-outline-success" title="Activar" onclick="confirmActivate(\'' . $afiliado->id . '\')">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>';
                    } else {
                        $actions .= '
                        <form id="deactivate-form-' . $afiliado->id . '" action="' . route('afiliados.desactivar', $afiliado->id) . '" method="POST" style="display:inline-block">
                            ' . csrf_field() . '
                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Desactivar" onclick="confirmDeactivate(\'' . $afiliado->id . '\')">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>';
                    }
                }

                $actions .= '</div>';
                return $actions;
            })
            ->addColumn('nombre_completo', function ($afiliado) {
                return $afiliado->nombre_completo;
            })
            ->addColumn('ci_completo', function ($afiliado) {
                return $afiliado->ci . ' ' . $afiliado->expedicion;
            })
            ->addColumn('reportes_pdf', function ($afiliado) {
                $reportes = '<div class="reportes-group">';
                
                // Carnet PDF (solo para afiliados activos)
                if ($afiliado->estado === 'ACTIVO' && !$afiliado->trashed()) {
                    $reportes .= '<a href="' . route('afiliados.carnet.pdf', $afiliado->id) . '" class="btn btn-danger btn-sm me-1" target="_blank" title="Generar Carnet PDF">';
                    $reportes .= '<i class="fas fa-id-card"></i>';
                    $reportes .= '</a>';
                }
                
                // Contrato
                $reportes .= '<button class="btn btn-info btn-sm me-1" onclick="alert(\'Contrato en desarrollo\')" title="Contrato">';
                $reportes .= '<i class="fas fa-file-alt"></i>';
                $reportes .= '</button>';
                
              
                
                $reportes .= '</div>';
                
                return $reportes;
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && !empty($request->search['value'])) {
                    $keyword = strtolower($request->search['value']);
                    
                    $query->where(function ($q) use ($keyword) {
                        // Buscar en campos directos
                        $q->whereRaw('LOWER(nombres) LIKE ?', ["%{$keyword}%"])
                        ->orWhereRaw('LOWER(apellido_paterno) LIKE ?', ["%{$keyword}%"])
                        ->orWhereRaw('LOWER(apellido_materno) LIKE ?', ["%{$keyword}%"])
                        ->orWhereRaw('LOWER(CONCAT_WS(\' \', apellido_paterno, apellido_materno, nombres)) LIKE ?', ["%{$keyword}%"])
                        
                        // Buscar en CI
                        ->orWhereRaw('LOWER(ci) LIKE ?', ["%{$keyword}%"])
                        ->orWhereRaw('LOWER(expedicion) LIKE ?', ["%{$keyword}%"])
                        ->orWhereRaw('LOWER(CONCAT_WS(\' \', ci, expedicion)) LIKE ?', ["%{$keyword}%"])
                        
                        // Buscar en celular y dirección
                        ->orWhereRaw('LOWER(celular) LIKE ?', ["%{$keyword}%"])
                        ->orWhereRaw('LOWER(direccion) LIKE ?', ["%{$keyword}%"])
                        ->orWhereRaw('LOWER(estado) LIKE ?', ["%{$keyword}%"])
                        
                        // Buscar en relaciones (usando subconsultas)
                        ->orWhereHas('ciudad', function ($q) use ($keyword) {
                            $q->whereRaw('LOWER(nombre) LIKE ?', ["%{$keyword}%"]);
                        })
                        ->orWhereHas('profesionTecnica', function ($q) use ($keyword) {
                            $q->whereRaw('LOWER(nombre) LIKE ?', ["%{$keyword}%"]);
                        })
                        ->orWhereHas('especialidad', function ($q) use ($keyword) {
                            $q->whereRaw('LOWER(nombre) LIKE ?', ["%{$keyword}%"]);
                        })
                        ->orWhereHas('sectorEconomico', function ($q) use ($keyword) {
                            $q->whereRaw('LOWER(nombre) LIKE ?', ["%{$keyword}%"]);
                        })
                        ->orWhereHas('organizacionSocial', function ($q) use ($keyword) {
                            $q->whereRaw('LOWER(nombre) LIKE ?', ["%{$keyword}%"]);
                        });
                    });
                }
            })
            ->editColumn('fecha_nacimiento', function ($afiliado) {
                return $afiliado->fecha_nacimiento ? $afiliado->fecha_nacimiento->format('d/m/Y') : '';
            })
            ->editColumn('fecha_afiliacion', function ($afiliado) {
                return $afiliado->fecha_afiliacion ? $afiliado->fecha_afiliacion->format('d/m/Y') : '';
            })
            ->editColumn('fecha_vencimiento', function ($afiliado) {
                return $afiliado->fecha_vencimiento ? $afiliado->fecha_vencimiento->format('d/m/Y') : '';
            })
            ->editColumn('ciudad_id', function ($afiliado) {
                return $afiliado->ciudad->nombre ?? 'N/A';
            })
            ->editColumn('profesion_tecnica_id', function ($afiliado) {
                return $afiliado->profesionTecnica->nombre ?? 'N/A';
            })
            ->editColumn('especialidad_id', function ($afiliado) {
                return $afiliado->especialidad->nombre ?? 'N/A';
            })
            ->editColumn('sector_economico_id', function ($afiliado) {
                return $afiliado->sectorEconomico->nombre ?? 'N/A';
            })
            ->editColumn('estado', function ($afiliado) {
                if ($afiliado->trashed()) {
                    return '<span class="badge bg-secondary">Eliminado</span>';
                }
                
                $badgeClass = [
                    'ACTIVO' => 'bg-success',
                    'INACTIVO' => 'bg-warning',
                    'PENDIENTE' => 'bg-info'
                ][$afiliado->estado] ?? 'bg-secondary';
                
                return '<span class="badge ' . $badgeClass . '">' . $afiliado->estado . '</span>';
            })
            ->rawColumns(['actions', 'estado', 'sector_economico_id', 'reportes_pdf']) // AQUÍ ESTÁ LA CORRECCIÓN
            ->make(true);
    }

    return view('afiliados.index');
}

    public function create()
    {
        $ciudades = Ciudad::orderBy('nombre')->get();
        $profesiones = ProfesionTecnica::orderBy('nombre')->get();
        $especialidades = Especialidad::orderBy('nombre')->get();
        $sectoresEconomicos = SectorEconomico::orderBy('nombre')->get();
        $organizacionesSociales = OrganizacionSocial::orderBy('nombre')->get();

        return view('afiliados.create', compact(
            'ciudades',
            'profesiones', 
            'especialidades',
            'sectoresEconomicos',
            'organizacionesSociales'
        ));
    }

    public function showPublic($id)
    {
        $afiliado = Afiliado::where('estado', 'ACTIVO')->findOrFail($id);
        
        return view('afiliados.verificacion-publica', compact('afiliado'));
    }

    // public function store(StoreAfiliadoRequest $request)
    // {
    //     // Verificar CI única (excluyendo eliminados)
    //     $afiliadoExistente = Afiliado::where('ci', $request->ci)->first();
    //     if ($afiliadoExistente) {
    //         return redirect()->back()
    //             ->withErrors(['ci' => 'Ya existe un afiliado activo con esta CI: ' . $afiliadoExistente->nombre_completo])
    //             ->withInput();
    //     }

    //     // Verificar si existe pero está eliminado
    //     $afiliadoEliminado = Afiliado::onlyTrashed()->where('ci', $request->ci)->first();
    //     if ($afiliadoEliminado) {
    //         $request->session()->flash('afiliado_eliminado_data', [
    //             'id' => $afiliadoEliminado->id,
    //             'nombre_completo' => $afiliadoEliminado->nombre_completo,
    //             'ci' => $afiliadoEliminado->ci,
    //             'expedicion' => $afiliadoEliminado->expedicion
    //         ]);
            
    //         $request->session()->flash('form_data', $request->all());
            
    //         return redirect()->back()->withInput();
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $afiliado = new Afiliado();
            
    //         // Usar datos validados del Request
    //         $validatedData = $request->validated();
            
    //         // Convertir fecha de formato d/m/Y a Y-m-d para la base de datos
    //         if (!empty($validatedData['fecha_nacimiento'])) {
    //             $validatedData['fecha_nacimiento'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validatedData['fecha_nacimiento'])->format('Y-m-d');
    //         }
            
    //         $afiliado->fill($validatedData);
            
    //         // Manejar foto
    //         if ($request->hasFile('foto')) {
    //             $path = $request->file('foto')->store('afiliados/fotos', 'public');
    //             $afiliado->foto_path = $path;
    //         } elseif ($request->filled('foto_data')) {
    //             // Guardar base64 como archivo
    //             $imageData = $request->foto_data;
    //             $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
                
    //             $fileName = 'foto_' . time() . '_' . $afiliado->ci . '.jpg';
    //             $path = 'afiliados/fotos/' . $fileName;
                
    //             Storage::disk('public')->put($path, $imageData);
    //             $afiliado->foto_path = $path;
    //         }

    //         // === NUEVO: MANEJO DE HUELLA ===
    //         if ($request->hasFile('huella')) {
    //             $huellaPath = $request->file('huella')->store('afiliados/huellas', 'public');
    //             $afiliado->huella_path = $huellaPath;
    //         } elseif ($request->filled('huella_data')) {
    //             // (Futura funcionalidad con cámara)
    //             $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->huella_data));
    //             $fileName = 'huella_' . time() . '_' . $afiliado->ci . '.jpg';
    //             $path = 'afiliados/huellas/' . $fileName;
    //             Storage::disk('public')->put($path, $imageData);
    //             $afiliado->huella_path = $path;
    //         }

            

            
    //         $afiliado->save();
            
    //         DB::commit();
            
    //         return redirect()->route('afiliados.index')
    //             ->with('success', 'Afiliado registrado exitosamente.');
                
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()
    //             ->with('error', 'Error al registrar afiliado: ' . $e->getMessage())
    //             ->withInput();
    //     }
    // }

    public function store(StoreAfiliadoRequest $request)
{
    // Verificar CI única (excluyendo eliminados)
    $afiliadoExistente = Afiliado::where('ci', $request->ci)->first();
    if ($afiliadoExistente) {
        return redirect()->back()
            ->withErrors(['ci' => 'Ya existe un afiliado activo con esta CI: ' . $afiliadoExistente->nombre_completo])
            ->withInput();
    }

    // Verificar si existe pero está eliminado
    $afiliadoEliminado = Afiliado::onlyTrashed()->where('ci', $request->ci)->first();
    if ($afiliadoEliminado) {
        $request->session()->flash('afiliado_eliminado_data', [
            'id' => $afiliadoEliminado->id,
            'nombre_completo' => $afiliadoEliminado->nombre_completo,
            'ci' => $afiliadoEliminado->ci,
            'expedicion' => $afiliadoEliminado->expedicion
        ]);
        
        $request->session()->flash('form_data', $request->all());
        
        return redirect()->back()->withInput();
    }

    DB::beginTransaction();
    try {
        $afiliado = new Afiliado();
        
        // Usar datos validados del Request
        $validatedData = $request->validated();
        
        // Convertir fecha de formato d/m/Y a Y-m-d para la base de datos
        if (!empty($validatedData['fecha_nacimiento'])) {
            $validatedData['fecha_nacimiento'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validatedData['fecha_nacimiento'])->format('Y-m-d');
        }
        
        $afiliado->fill($validatedData);
        
        // Manejar foto
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('afiliados/fotos', 'public');
            $afiliado->foto_path = $path;
        } elseif ($request->filled('foto_data')) {
            // Guardar base64 como archivo
            $imageData = $request->foto_data;
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
            
            $fileName = 'foto_' . time() . '_' . $afiliado->ci . '.jpg';
            $path = 'afiliados/fotos/' . $fileName;
            
            Storage::disk('public')->put($path, $imageData);
            $afiliado->foto_path = $path;
        }

        // Manejo de huella
        if ($request->hasFile('huella')) {
            $huellaPath = $request->file('huella')->store('afiliados/huellas', 'public');
            $afiliado->huella_path = $huellaPath;
        } elseif ($request->filled('huella_data')) {
            // (Futura funcionalidad con cámara)
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->huella_data));
            $fileName = 'huella_' . time() . '_' . $afiliado->ci . '.jpg';
            $path = 'afiliados/huellas/' . $fileName;
            Storage::disk('public')->put($path, $imageData);
            $afiliado->huella_path = $path;
        }

        // Guardar el afiliado primero para obtener el ID
        $afiliado->save();
        
        // === NUEVO: MANEJO DE ARCHIVOS ADICIONALES ===
        if ($request->has('archivos') && is_array($request->archivos)) {
            foreach ($request->archivos as $archivoData) {
                if (isset($archivoData['archivo']) && $archivoData['archivo']->isValid()) {
                    $archivo = $archivoData['archivo'];
                    
                    // Validar tamaño máximo (5MB)
                    if ($archivo->getSize() > 5 * 1024 * 1024) {
                        throw new \Exception("El archivo '{$archivo->getClientOriginalName()}' excede el tamaño máximo de 5MB");
                    }
                    
                    // Validar tipos de archivo permitidos
                    $extension = strtolower($archivo->getClientOriginalExtension());
                    $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
                    
                    if (!in_array($extension, $allowedExtensions)) {
                        throw new \Exception("El archivo '{$archivo->getClientOriginalName()}' tiene una extensión no permitida");
                    }
                    
                    // Generar nombre único
                    $nombreArchivo = 'doc_' . time() . '_' . uniqid() . '.' . $extension;
                    
                    // Guardar en storage
                    $ruta = $archivo->storeAs('archivos_afiliados', $nombreArchivo, 'public');
                    
                    // Crear registro en base de datos
                    ArchivoAfiliado::create([
                        'afiliado_id' => $afiliado->id,
                        'nombre_original' => $archivo->getClientOriginalName(),
                        'nombre_archivo' => $nombreArchivo,
                        'mime_type' => $archivo->getMimeType(),
                        'tamanio' => $archivo->getSize(),
                        'ruta' => $ruta,
                        'tipo_documento' => $archivoData['tipo'],
                        'descripcion' => $archivoData['descripcion'] ?? null,
                        'metadata' => [
                            'uploaded_by' => auth()->id(),
                            'uploaded_at' => now()->toIso8601String(),
                            'ip_address' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                        ]
                    ]);
                }
            }
        }
        
        DB::commit();
        
        return redirect()->route('afiliados.show', $afiliado->id)
            ->with('success', 'Afiliado registrado exitosamente.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        // Limpiar archivos subidos en caso de error
        if (isset($afiliado->foto_path)) {
            Storage::disk('public')->delete($afiliado->foto_path);
        }
        if (isset($afiliado->huella_path)) {
            Storage::disk('public')->delete($afiliado->huella_path);
        }
        
        return redirect()->back()
            ->with('error', 'Error al registrar afiliado: ' . $e->getMessage())
            ->withInput();
    }
}

    // Método para restaurar afiliado eliminado desde el formulario de creación
    public function restaurarDesdeCreate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $afiliado = Afiliado::withTrashed()->findOrFail($id);
            
            // Restaurar el afiliado
            $afiliado->restore();
            
            // Obtener datos del formulario desde la sesión
            $formData = $request->session()->get('form_data');
            
            // Actualizar datos si hay información nueva del formulario
            if ($formData) {
                $datosActualizar = [
                    'nombres' => $formData['nombres'] ?? $afiliado->nombres,
                    'apellido_paterno' => $formData['apellido_paterno'] ?? $afiliado->apellido_paterno,
                    'apellido_materno' => $formData['apellido_materno'] ?? $afiliado->apellido_materno,
                    'celular' => $formData['celular'] ?? $afiliado->celular,
                    'direccion' => $formData['direccion'] ?? $afiliado->direccion,
                    'ciudad_id' => $formData['ciudad_id'] ?? $afiliado->ciudad_id,
                    'profesion_tecnica_id' => $formData['profesion_tecnica_id'] ?? $afiliado->profesion_tecnica_id,
                    'especialidad_id' => $formData['especialidad_id'] ?? $afiliado->especialidad_id,
                    'organizacion_social_id' => $formData['organizacion_social_id'] ?? $afiliado->organizacion_social_id,
                    'sector_economico_id' => $formData['sector_economico_id'] ?? $afiliado->sector_economico_id,
                    'genero' => $formData['genero'] ?? $afiliado->genero,
                ];
                
                // Manejar fecha de nacimiento
                if (!empty($formData['fecha_nacimiento'])) {
                    $datosActualizar['fecha_nacimiento'] = \Carbon\Carbon::createFromFormat('d/m/Y', $formData['fecha_nacimiento'])->format('Y-m-d');
                }
                
                // Manejar foto si se proporciona una nueva
                if (isset($formData['foto_data']) && !empty($formData['foto_data'])) {
                    // Guardar base64 como archivo
                    $imageData = $formData['foto_data'];
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
                    
                    // Eliminar foto anterior si existe
                    if ($afiliado->foto_path) {
                        Storage::disk('public')->delete($afiliado->foto_path);
                    }
                    
                    $fileName = 'foto_' . time() . '_' . $afiliado->ci . '.jpg';
                    $path = 'afiliados/fotos/' . $fileName;
                    
                    Storage::disk('public')->put($path, $imageData);
                    $datosActualizar['foto_path'] = $path;
                    $datosActualizar['foto_data'] = null;
                }
                
                $afiliado->update($datosActualizar);
            }
            
            DB::commit();
            
            // Limpiar datos de sesión
            $request->session()->forget(['afiliado_eliminado_data', 'form_data']);
            
            return redirect()->route('afiliados.index')
                ->with('success', 'Afiliado restaurado y actualizado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('afiliados.create')
                ->with('error', 'Error al restaurar afiliado: ' . $e->getMessage())
                ->withInput();
        }
    }

    // public function show($id)
    // {
    //     $afiliado = Afiliado::with([
    //         'ciudad', 
    //         'profesionTecnica', 
    //         'especialidad', 
    //         'sectorEconomico',
    //         'organizacionSocial'
    //     ])->withTrashed()->findOrFail($id);
        
    //     return view('afiliados.show', compact('afiliado'));
    // }

    public function show($id)
    {
        $afiliado = Afiliado::with(['ciudad', 'profesionTecnica', 'especialidad', 
                                'organizacionSocial', 'sectorEconomico', 'archivos']) // <-- Agrega 'archivos'
                        ->withTrashed()
                        ->findOrFail($id);
        
        return view('afiliados.show', compact('afiliado'));
    }

    public function edit($id)
    {
        $afiliado = Afiliado::with([
            'ciudad', 
            'profesionTecnica', 
            'especialidad', 
            'sectorEconomico',
            'organizacionSocial'
        ])->findOrFail($id);

        $ciudades = Ciudad::orderBy('nombre')->get();
        $profesiones = ProfesionTecnica::orderBy('nombre')->get();
        $especialidades = Especialidad::orderBy('nombre')->get();
        $sectoresEconomicos = SectorEconomico::orderBy('nombre')->get();
        $organizacionesSociales = OrganizacionSocial::orderBy('nombre')->get();

        return view('afiliados.edit', compact(
            'afiliado',
            'ciudades',
            'profesiones', 
            'especialidades',
            'sectoresEconomicos',
            'organizacionesSociales'
        ));
    }

    public function update(UpdateAfiliadoRequest $request, $id)
    {
        $afiliado = Afiliado::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Usar datos validados del Request
            $validatedData = $request->validated();
            
            // Convertir fecha de formato d/m/Y a Y-m-d para la base de datos
            if (!empty($validatedData['fecha_nacimiento'])) {
                $validatedData['fecha_nacimiento'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validatedData['fecha_nacimiento'])->format('Y-m-d');
            }
            
            $afiliado->fill($validatedData);
            
            // Manejar foto si se proporciona una nueva
            if ($request->hasFile('foto')) {
                // Eliminar foto anterior si existe
                if ($afiliado->foto_path) {
                    Storage::disk('public')->delete($afiliado->foto_path);
                }
                
                $path = $request->file('foto')->store('afiliados/fotos', 'public');
                $afiliado->foto_path = $path;
                $afiliado->foto_data = null;
                
            } elseif ($request->filled('foto_data')) {
                // Guardar base64 como archivo
                $imageData = $request->foto_data;
                $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
                
                // Eliminar foto anterior si existe
                if ($afiliado->foto_path) {
                    Storage::disk('public')->delete($afiliado->foto_path);
                }
                
                $fileName = 'foto_' . time() . '_' . $afiliado->ci . '.jpg';
                $path = 'afiliados/fotos/' . $fileName;
                
                Storage::disk('public')->put($path, $imageData);
                $afiliado->foto_path = $path;
                $afiliado->foto_data = null;
            }
            
            $afiliado->save();
            
            DB::commit();
            
            return redirect()->route('afiliados.index')
                ->with('success', 'Afiliado actualizado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar afiliado: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $afiliado = Afiliado::findOrFail($id);
        $afiliado->delete();
        
        return redirect()->route('afiliados.index')
            ->with('success', 'Afiliado eliminado exitosamente.');
    }

    public function activar($id)
    {
        $afiliado = Afiliado::findOrFail($id);
        $afiliado->activar();
        
        return redirect()->route('afiliados.index')
            ->with('success', 'Afiliado activado exitosamente.');
    }

    public function desactivar($id)
    {
        $afiliado = Afiliado::findOrFail($id);
        $afiliado->desactivar();
        
        return redirect()->route('afiliados.index')
            ->with('success', 'Afiliado desactivado exitosamente.');
    }

    public function restaurar($id)
    {
        $afiliado = Afiliado::withTrashed()->findOrFail($id);
        $afiliado->restore();
        
        return redirect()->route('afiliados.index')
            ->with('success', 'Afiliado restaurado exitosamente.');
    }

    public function carnetPdf($id)
    {
        $afiliado = Afiliado::with([
            'ciudad', 
            'profesionTecnica', 
            'especialidad', 
            'sectorEconomico'
        ])->findOrFail($id);
        
        // Aquí iría la lógica para generar el PDF del carnet
        // Por ahora retornamos un mensaje
        return redirect()->route('afiliados.index')
            ->with('info', 'Funcionalidad de PDF en desarrollo para: ' . $afiliado->nombre_completo);
    }

    public function buscarPorCi($ci)
    {
        $afiliado = Afiliado::with([
            'ciudad', 
            'profesionTecnica', 
            'especialidad', 
            'sectorEconomico'
        ])->where('ci', $ci)->first();
        
        if ($afiliado) {
            return response()->json([
                'success' => true,
                'data' => [
                    'ci' => $afiliado->ci,
                    'expedicion' => $afiliado->expedicion,
                    'nombres' => $afiliado->nombres,
                    'apellido_paterno' => $afiliado->apellido_paterno,
                    'apellido_materno' => $afiliado->apellido_materno,
                    'fecha_nacimiento' => $afiliado->fecha_nacimiento ? $afiliado->fecha_nacimiento->format('d/m/Y') : null,
                    'genero' => $afiliado->genero,
                    'celular' => $afiliado->celular,
                    'ciudad' => $afiliado->ciudad_id, // ID para el select
                    'direccion' => $afiliado->direccion,
                    'profesion_tecnica' => $afiliado->profesion_tecnica_id, // ID
                    'especialidad' => $afiliado->especialidad_id, // ID
                    'organizacion_social' => $afiliado->organizacion_social_id, // ID
                    'sector_economico' => $afiliado->sector_economico_id, // ID
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Afiliado no encontrado'
        ]);
    }


    public function storeArchivo(Request $request, $id)
    {
        $request->validate([
            'tipo' => 'required|in:DNI,CERTIFICADO,CONTRATO,FOTO_EXTRA,OTRO',
            'descripcion' => 'nullable|string|max:255',
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);
        
        $afiliado = Afiliado::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $archivo = $request->file('archivo');
            
            // Generar nombre único
            $nombreArchivo = 'doc_' . time() . '_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
            
            // Guardar en storage
            $ruta = $archivo->storeAs('archivos_afiliados', $nombreArchivo, 'public');
            
            // Crear registro en base de datos
            ArchivoAfiliado::create([
                'afiliado_id' => $afiliado->id,
                'nombre_original' => $archivo->getClientOriginalName(),
                'nombre_archivo' => $nombreArchivo,
                'mime_type' => $archivo->getMimeType(),
                'tamanio' => $archivo->getSize(),
                'ruta' => $ruta,
                'tipo_documento' => $request->tipo,
                'descripcion' => $request->descripcion,
                'metadata' => [
                    'uploaded_by' => auth()->id(),
                    'uploaded_at' => now()->toIso8601String(),
                    'ip_address' => $request->ip(),
                ]
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Documento agregado correctamente.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al agregar documento: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un archivo
     */
    public function destroyArchivo($afiliadoId, $archivoId)
    {
        $archivo = ArchivoAfiliado::where('afiliado_id', $afiliadoId)->findOrFail($archivoId);
        
        DB::beginTransaction();
        try {
            // Eliminar archivo físico
            Storage::disk('public')->delete($archivo->ruta);
            
            // Eliminar registro
            $archivo->delete();
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Documento eliminado correctamente.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al eliminar documento: ' . $e->getMessage());
        }
    }
}