@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow border-success">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-users me-2"></i>Gestión de Usuarios</h4>
                        <span class="badge bg-light text-dark">{{ $usuarios->count() }} usuarios</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Barra de búsqueda -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.users') }}" class="d-flex">
                                <input type="text" name="busqueda" value="{{ $busqueda }}" 
                                       class="form-control me-2" placeholder="Buscar por nombre...">
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if($busqueda)
                                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary ms-2">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>

                    <!-- Tabla de usuarios -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th>Fecha Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuarios as $usuario)
                                    <tr>
                                        <td>{{ $usuario->id }}</td>
                                        <td>
                                            <strong>{{ $usuario->name }}</strong>
                                            @if($usuario->rol === 'admin')
                                                <span class="badge bg-danger ms-1">Admin</span>
                                            @endif
                                        </td>
                                        <td>{{ $usuario->email }}</td>
                                        <td>
                                            @if($usuario->rol === 'admin')
                                                <span class="badge bg-danger">Administrador</span>
                                            @else
                                                <span class="badge bg-success">Usuario</span>
                                            @endif
                                        </td>
                                        <td>{{ $usuario->telefono ?? 'No especificado' }}</td>
                                        <td>{{ $usuario->direccion ?? 'No especificada' }}</td>
                                        <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.users.edit', $usuario) }}" 
                                                   class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                                @if($usuario->id !== auth()->id())
                                                    <form method="POST" action="{{ route('admin.users.destroy', $usuario) }}" 
                                                          style="display: inline;" onsubmit="return confirmarEliminacion()">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i> Eliminar
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No se encontraron usuarios</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminacion() {
    return confirm('¿Estás seguro de que quieres eliminar este usuario? Esta acción no se puede deshacer.');
}
</script>
@endsection 