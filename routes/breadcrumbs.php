<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
// Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
//     $trail->push('Inicio', route('home'));
// });

// // Dashboard
Breadcrumbs::for('admin', function (BreadcrumbTrail $trail) {
    // $trail->parent('home');
    // $trail->push('Dashboard', route('dashboard'));
});

// Roles
Breadcrumbs::for('roles.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin');
    $trail->push('Roles', route('admin.roles.index'));
});

// Crear Rol
Breadcrumbs::for('roles.create', function (BreadcrumbTrail $trail) {
    $trail->parent('roles.index');
    $trail->push('Crear Rol', route('admin.roles.create'));
});

// Editar Rol
Breadcrumbs::for('roles.edit', function (BreadcrumbTrail $trail, $role) {
    $trail->parent('roles.index');
    $trail->push('Editar Rol: ' . $role->name, route('admin.roles.edit', $role));
});

// autorizaciones individuales  
// Breadcrumbs::for('autorizaciones-individuales.index', function (BreadcrumbTrail $trail) {
//     $trail->parent('admin');
//     $trail->push('Autorizaciones Individuales', route('admin.autorizaciones-individuales.index'));
// });
