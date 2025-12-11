<?php
// app/Models/Afiliado.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Afiliado extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'afiliados';

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'ci',
        'expedicion',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'genero',
        'celular',
        'direccion', 
        'ciudad_id',
        'profesion_tecnica_id',
        'especialidad_id',
        'organizacion_social_id', 
        'sector_economico_id', 
        'foto_path',
        'foto_data',
        'huella_path',
        'huella_data',
        'estado',
        'fecha_afiliacion',
        'fecha_vencimiento'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_afiliacion' => 'datetime',
        'fecha_vencimiento' => 'datetime',
        'id' => 'string'
    ];

    protected $attributes = [
        'estado' => 'PENDIENTE'
    ];

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', 'ACTIVO');
    }

    public function scopeInactivos($query)
    {
        return $query->where('estado', 'INACTIVO');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'PENDIENTE');
    }

    // Métodos
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}");
    }

    public function getEdadAttribute()
    {
        return $this->fecha_nacimiento?->age;
    }

    public function activar()
    {
        $this->update([
            'estado' => 'ACTIVO',
            'fecha_afiliacion' => now(),
            'fecha_vencimiento' => now()->addYears(2)
        ]);
    }

    public function desactivar()
    {
        $this->update(['estado' => 'INACTIVO']);
    }

    public function getFotoUrlAttribute()
    {
        if ($this->foto_path) {
            return asset('storage/' . $this->foto_path);
        }
        
        if ($this->foto_data) {
            return $this->foto_data;
        }
        
        return 
        null;
    }

    // Accessor para URL de huella
    public function getHuellaUrlAttribute()
    {
        if ($this->huella_path) {
            return asset('storage/' . $this->huella_path);
        }
        if ($this->huella_data) {
            return $this->huella_data;
        }
        return asset('images/no-huella.png'); // opcional: imagen por defecto
    }

    public function scopeConCi($query, $ci)
    {
        return $query->where('ci', $ci);
    }

    public function scopeEliminadoConCi($query, $ci)
    {
        return $query->onlyTrashed()->where('ci', $ci);
    }

    // Relaciones - NOMBRES CORREGIDOS
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class);
    }

  

    public function organizacionSocial()
    {
        return $this->belongsTo(OrganizacionSocial::class);
    }

    public function sectorEconomico()
    {
        return $this->belongsTo(SectorEconomico::class);
    }

    public function profesionTecnica()
    {
        return $this->belongsTo(ProfesionTecnica::class, 'profesion_tecnica_id');
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class);
    }




    // Agrega este método al final de la clase
    public function archivos()
    {
        return $this->hasMany(ArchivoAfiliado::class);
    }

    // Método para obtener archivos por tipo
    public function archivosPorTipo($tipo)
    {
        return $this->archivos()->where('tipo_documento', $tipo)->get();
    }

    // Método para contar archivos
    public function getCantidadArchivosAttribute()
    {
        return $this->archivos()->count();
    }



}