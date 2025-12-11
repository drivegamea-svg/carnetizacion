<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizacionSocial extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'organizaciones_sociales';

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
        return $this->hasMany(Afiliado::class, 'organizacion_social_id');
    }
}