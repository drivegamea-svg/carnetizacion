<?php
// app/Models/ArchivoAfiliado.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ArchivoAfiliado extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'archivos_afiliados';

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'afiliado_id',
        'nombre_original',
        'nombre_archivo',
        'mime_type',
        'tamanio',
        'ruta',
        'tipo_documento',
        'descripcion',
        'metadata'
    ];

    protected $casts = [
        'tamanio' => 'integer',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Tipos de documento permitidos
    public const TIPOS_DOCUMENTO = [
        'DNI' => 'Documento de Identidad',
        'CERTIFICADO' => 'Certificado',
        'CONTRATO' => 'Contrato',
        'FOTO_EXTRA' => 'Foto Adicional',
        'OTRO' => 'Otro Documento'
    ];

    // Relación con afiliado
    public function afiliado()
    {
        return $this->belongsTo(Afiliado::class);
    }

    // Accessor para URL del archivo
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->ruta);
    }

    // Accessor para tamaño formateado
    public function getTamanioFormateadoAttribute()
    {
        $bytes = $this->tamanio;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }

    // Scope para filtrar por tipo
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_documento', $tipo);
    }

    // Método para obtener la extensión
    public function getExtensionAttribute()
    {
        return pathinfo($this->nombre_original, PATHINFO_EXTENSION);
    }

    // Método para verificar si es imagen
    public function getEsImagenAttribute()
    {
        return str_starts_with($this->mime_type, 'image/');
    }
}