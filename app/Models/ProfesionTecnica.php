<?php
// app/Models/ProfesionTecnica.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfesionTecnica extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'profesiones_tecnicas';

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
        return $this->hasMany(Afiliado::class, 'profesion_tecnica_id');
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