<?php

// app/Models/Role.php
namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends SpatieRole
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\User::class,
            'model_has_roles',
            'role_id',
            'model_id'
        );
    }
}