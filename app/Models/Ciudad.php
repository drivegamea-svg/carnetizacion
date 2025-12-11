<?php
// app/Models/Ciudad.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ciudad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ciudades';

    protected $fillable = ['nombre', 'departamento'];

    // Scope para forzar nombres únicos solo entre activos
    public function scopeNombreUnico($query, $nombre, $ignoreId = null)
    {
        $query->where('nombre', $nombre)
              ->whereNull('deleted_at');
              
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        
        return $query;
    }

    // Scope para filtrar por departamento
    public function scopePorDepartamento($query, $departamento)
    {
        return $query->where('departamento', $departamento);
    }

    // Scope para obtener departamentos únicos
    public function scopeDepartamentos($query)
    {
        return $query->select('departamento')->distinct()->orderBy('departamento');
    }

    public function afiliados()
    {
        return $this->hasMany(Afiliado::class, 'ciudad_id');
    }

    // Método para obtener estadísticas
    public function getEstadisticasAttribute()
    {
        return [
            'total_afiliados' => $this->afiliados()->count(),
            'afiliados_activos' => $this->afiliados()->activos()->count(),
        ];
    }
}