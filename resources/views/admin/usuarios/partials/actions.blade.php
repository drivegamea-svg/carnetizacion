{{-- resources/views/admin/usuarios/partials/actions.blade.php --}}
<div class="btn-group">
    @can('editar usuarios')
        <a href="{{ route('admin.usuarios.edit', $user->id) }}" class="btn btn-sm btn-primary">Editar</a>
    @endcan

    @can('eliminar usuarios')
        <form action="{{ route('admin.usuarios.destroy', $user->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
        </form>
    @endcan
</div>
