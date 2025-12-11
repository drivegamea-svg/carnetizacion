<?php
// app/Models/Especialidad.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Especialidad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'especialidades';

    protected $fillable = ['nombre', 'descripcion'];

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

    public function afiliados()
    {
        return $this->hasMany(Afiliado::class, 'especialidad_id');
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