@extends('layouts.app')

@section('content')
<button class="sidebar-toggler" id="sidebarToggle"><i class="fas fa-bars"></i></button>
<div class="sidebar d-flex flex-column justify-content-between" id="sidebarMenu">
    <div>
        <div class="user-name mb-4">
            {{ strtoupper(auth()->user()->name) }}
        </div>
        <a href="{{ route('home.index') }}" class="nav-link-custom">
            <i class="fas fa-home me-2"></i> Home
        </a>
        @if(auth()->user()->rol === 'admin')
        <a href="{{ route('notas.search') }}" class="nav-link-custom active">
            <i class="fas fa-users me-2"></i> Gestión de Alumnos
        </a>
        <a href="{{ route('admin.importar-home') }}" class="nav-link-custom">
            <i class="fas fa-file-import me-2"></i> Importar Datos
        </a>
        <a href="{{ route('admin.users') }}" class="nav-link-custom">
            <i class="fas fa-users-cog me-2"></i> Gestionar Usuarios
        </a>
        @else
        <a href="{{ route('notas.mis-notas') }}" class="nav-link-custom">
            <i class="fas fa-graduation-cap me-2"></i> Mis Notas
        </a>
        @endif
        <a href="{{ route('profile.show') }}" class="nav-link-custom">
            <i class="fas fa-user-circle me-2"></i> Mi Perfil
        </a>
        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="nav-link-custom" style="width:100%;text-align:left;background:#c0392b;color:#fff;font-weight:bold;">
                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
            </button>
        </form>
    </div>
    <div class="logo">
        <img src="/img/logo-carabineros.png" alt="Logo Carabineros">
    </div>
</div>
<div class="main-content">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow border-success">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0"><i class="fas fa-users me-2"></i>Gestión de Alumnos</h4>
                            <span class="d-flex align-items-center" style="gap: 0.5rem;">
                                <span class="fs-5 fw-bold badge rounded-pill bg-light text-success px-4 py-2" style="font-size:1.3rem;box-shadow:0 2px 8px rgba(25,135,84,0.10);">
                                    <i class="fas fa-user-friends me-2"></i> {{ $usuarios->count() }}
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filtros -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <form method="GET" action="{{ route('notas.search') }}" class="d-flex gap-2">
                                    <input type="text" name="busqueda" value="{{ request('busqueda') }}" 
                                           class="form-control" placeholder="Buscar por nombre o código...">
                                    <select name="escuela" class="form-select" style="width: auto;">
                                        <option value="">Todas las escuelas</option>
                                        <option value="1" {{ request('escuela') == '1' ? 'selected' : '' }}>Esucar</option>
                                        <option value="2" {{ request('escuela') == '2' ? 'selected' : '' }}>Acipol</option>
                                    </select>
                                    <button type="submit" class="btn btn-outline-success">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request('busqueda') || request('escuela'))
                                        <a href="{{ route('notas.search') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </form>
                            </div>
                        </div>

                        <!-- Tabla de usuarios -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-success">
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Escuela</th>
                                        <th>Total Notas</th>
                                        <th>Promedio</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($usuarios as $usuario)
                                        <tr>
                                            <td><strong>{{ $usuario->codigo_funcionario }}</strong></td>
                                            <td>
                                                @php
                                                    $notasUsuario = $notas->where('user_id', $usuario->id);
                                                    $gradoUsuario = $notasUsuario->first() ? $notasUsuario->first()->grado : null;
                                                @endphp
                                                @if($gradoUsuario)
                                                    {{ $gradoUsuario }} {{ $usuario->name }}
                                                @else
                                                    {{ $usuario->name }}
                                                @endif
                                            </td>
                                            <td>{{ $usuario->email }}</td>
                                            <td>
                                                @if($usuario->escuela == 1)
                                                    <span class="badge bg-primary">Esucar</span>
                                                @elseif($usuario->escuela == 2)
                                                    <span class="badge bg-info">Acipol</span>
                                                @else
                                                    <span class="badge bg-secondary">No especificada</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $notasUsuario = $notas->where('user_id', $usuario->id);
                                                    $totalNotas = $notasUsuario->count();
                                                    $promedio = $totalNotas > 0 ? number_format($notasUsuario->avg('nota_final'), 1) : 0;
                                                @endphp
                                                <span class="badge bg-success">{{ $totalNotas }}</span>
                                            </td>
                                            <td>
                                                @if($promedio > 0)
                                                    <span class="badge bg-{{ $promedio >= 60 ? 'success' : 'danger' }}">{{ $promedio }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-success" onclick="toggleNotas({{ $usuario->id }})">
                                                    <i class="fas fa-eye"></i> Ver Notas
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- Notas del usuario (ocultas por defecto) -->
                                        <tr id="notas-{{ $usuario->id }}" style="display: none;">
                                            <td colspan="7">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">
                                                            Notas de 
                                                            @if($gradoUsuario)
                                                                {{ $gradoUsuario }} {{ $usuario->name }}
                                                            @else
                                                                {{ $usuario->name }}
                                                            @endif
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        @if($notasUsuario->count() > 0)
                                                            <div class="table-responsive">
                                                                <table class="table table-sm">
                                                                    <thead class="table-dark">
                                                                        <tr>
                                                                            <th>Fecha</th>
                                                                            <th>Grado</th>
                                                                            <th>Unidad</th>
                                                                            <th>Escrito</th>
                                                                            <th>Oral</th>
                                                                            <th>Físico</th>
                                                                            <th>Final</th>
                                                                            <th>Situación</th>
                                                                            <th>Acciones</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($notasUsuario as $nota)
                                                                            <tr>
                                                                                <td>{{ $nota->fecha_carga ? $nota->fecha_carga->format('d/m/Y') : '-' }}</td>
                                                                                <td>{{ $nota->grado }}</td>
                                                                                <td>{{ $nota->unidad }}</td>
                                                                                <td>
                                                                                    @if($nota->nota_1)
                                                                                        <span class="badge bg-{{ $nota->nota_1 >= 60 ? 'success' : 'danger' }}">
                                                                                            {{ $nota->nota_1 }}
                                                                                        </span>
                                                                                    @else
                                                                                        <span class="text-muted">-</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    @if($nota->nota_2)
                                                                                        <span class="badge bg-{{ $nota->nota_2 >= 60 ? 'success' : 'danger' }}">
                                                                                            {{ $nota->nota_2 }}
                                                                                        </span>
                                                                                    @else
                                                                                        <span class="text-muted">-</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    @if($nota->nota_3)
                                                                                        <span class="badge bg-{{ $nota->nota_3 >= 60 ? 'success' : 'danger' }}">
                                                                                            {{ $nota->nota_3 }}
                                                                                        </span>
                                                                                    @else
                                                                                        <span class="text-muted">-</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    @if($nota->nota_final)
                                                                                        <span class="badge bg-{{ $nota->nota_final >= 60 ? 'success' : 'danger' }} fs-6">
                                                                                            {{ $nota->nota_final }}
                                                                                        </span>
                                                                                    @else
                                                                                        <span class="text-muted">-</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    @if($nota->situacion)
                                                                                        <span class="badge bg-{{ strtolower($nota->situacion) == 'aprobado' ? 'success' : 'danger' }}">
                                                                                            {{ $nota->situacion }}
                                                                                        </span>
                                                                                    @else
                                                                                        <span class="text-muted">-</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    <div class="btn-group" role="group">
                                                                                        <a href="{{ route('notas.edit', $nota->id) }}" 
                                                                                           class="btn btn-sm btn-outline-primary">
                                                                                            <i class="fas fa-edit"></i>
                                                                                        </a>
                                                                                        <form method="POST" action="{{ route('notas.destroy', $nota->id) }}" 
                                                                                              style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta nota?')">
                                                                                            @csrf
                                                                                            @method('DELETE')
                                                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                                                <i class="fas fa-trash"></i>
                                                                                            </button>
                                                                                        </form>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @else
                                                            <p class="text-muted text-center">No hay notas registradas para este alumno.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No se encontraron alumnos</p>
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
</div>

<style>
    .sidebar {
        min-height: 100vh;
        height: 100vh;
        background: #198754;
        color: #fff;
        padding: 2rem 1rem 1rem 1rem;
        border-radius: 0 20px 20px 0;
        box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        position: fixed;
        top: 0;
        left: -320px;
        width: 320px;
        z-index: 1040;
        transition: left 0.5s cubic-bezier(.77,0,.18,1);
    }
    .sidebar.open {
        left: 0;
    }
    .sidebar-toggler {
        display: block;
        position: fixed;
        top: 1.2rem;
        left: 10px;
        background: #198754;
        border: none;
        color: #fff;
        border-radius: 6px;
        padding: 10px 16px;
        z-index: 2000;
        font-size: 2rem;
        box-shadow: 2px 2px 8px rgba(0,0,0,0.12);
        opacity: 0.95;
        transition: background 0.2s;
    }
    .sidebar .user-name {
        font-weight: bold;
        font-size: 1.1rem;
        margin-bottom: 2rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        text-align: center;
        word-break: break-word;
        white-space: normal;
    }
    .sidebar .nav-link-custom {
        color: #fff;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 4px;
        margin-bottom: 0.5rem;
        padding: 0.7rem 1rem;
        display: block;
        font-size: 1rem;
        transition: background 0.2s, color 0.2s, opacity 0.4s, transform 0.4s;
        text-decoration: none;
        opacity: 0;
        transform: translateX(-30px);
    }
    .sidebar .nav-link-custom.visible {
        opacity: 1;
        transform: translateX(0);
    }
    .sidebar .nav-link-custom:hover, .sidebar .nav-link-custom.active {
        background: rgba(255,255,255,0.12);
        color: #cddc39;
        text-decoration: none;
    }
    .sidebar .logo {
        margin-top: 2rem;
        text-align: center;
    }
    .sidebar .logo img {
        width: 60px;
        opacity: 0.85;
    }
    .main-content {
        margin-left: 0;
        transition: margin-left 0.5s cubic-bezier(.77,0,.18,1);
    }
    .sidebar.open ~ .main-content {
        margin-left: 320px;
    }
    @media (max-width: 991px) {
        .sidebar {
            border-radius: 0 10px 10px 0;
        }
        .sidebar.open ~ .main-content {
            margin-left: 0;
        }
    }
</style>

<script>
    const sidebar = document.getElementById('sidebarMenu');
    const toggleBtn = document.getElementById('sidebarToggle');
    const links = sidebar.querySelectorAll('.nav-link-custom, form button.nav-link-custom');
    
    // Verificar si el sidebar debe estar abierto (usando localStorage)
    const sidebarOpen = localStorage.getItem('sidebarOpen') === 'true';
    
    // Abrir sidebar con animación al cargar si estaba abierto
    window.addEventListener('DOMContentLoaded', function() {
        if (sidebarOpen) {
            setTimeout(() => {
                sidebar.classList.add('open');
                // Animación secuencial de links
                links.forEach((link, i) => {
                    setTimeout(() => link.classList.add('visible'), 200 + i * 120);
                });
            }, 200);
        } else {
            // Si no estaba abierto, mostrar solo los links sin animación
            links.forEach(link => link.classList.add('visible'));
        }
    });
    
    // Botón hamburguesa abre/cierra sidebar
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('open');
        const isOpen = sidebar.classList.contains('open');
        
        // Guardar estado en localStorage
        localStorage.setItem('sidebarOpen', isOpen);
        
        if(isOpen) {
            links.forEach((link, i) => {
                setTimeout(() => link.classList.add('visible'), 100 + i * 120);
            });
        } else {
            links.forEach(link => link.classList.remove('visible'));
        }
    });
    
    // Mantener sidebar contraído al hacer clic en enlaces
    links.forEach(link => {
        link.addEventListener('click', function() {
            // Cerrar sidebar si está abierto
            if (sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                localStorage.setItem('sidebarOpen', 'false');
                links.forEach(link => link.classList.remove('visible'));
            }
        });
    });
    
    // Función para mostrar/ocultar notas de un usuario
    function toggleNotas(userId) {
        const notasRow = document.getElementById(`notas-${userId}`);
        const button = event.target.closest('button');
        
        if (notasRow.style.display === 'none') {
            notasRow.style.display = 'table-row';
            button.innerHTML = '<i class="fas fa-eye-slash"></i> Ocultar Notas';
            button.classList.remove('btn-outline-success');
            button.classList.add('btn-outline-warning');
        } else {
            notasRow.style.display = 'none';
            button.innerHTML = '<i class="fas fa-eye"></i> Ver Notas';
            button.classList.remove('btn-outline-warning');
            button.classList.add('btn-outline-success');
        }
    }
</script>
@endsection 