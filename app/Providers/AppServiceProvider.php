<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Verificar y crear storage link al iniciar la aplicación
        $this->ensureStorageLinkExists();
    }

    protected function ensureStorageLinkExists()
    {
        try {
            $storagePath = public_path('storage');
            $targetPath = storage_path('app/public');
            
            // Solo ejecutar en entorno local o si no existe el enlace
            if (app()->environment('local') || !file_exists($storagePath)) {
                if (!file_exists($storagePath)) {
                    Artisan::call('storage:link');
                }
            }
            
            // Crear carpetas necesarias
            $directories = ['afiliados/fotos', 'afiliados/temp'];
            foreach ($directories as $directory) {
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory, 0755, true);
                }
            }
            
        } catch (\Exception $e) {
            \Log::warning('No se pudo crear storage link: ' . $e->getMessage());
        }
    }
}