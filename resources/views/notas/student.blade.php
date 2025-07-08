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
        <a href="{{ route('notas.search') }}" class="nav-link-custom">
            <i class="fas fa-users me-2"></i> Gestión de Alumnos
        </a>
        <a href="{{ route('admin.importar-home') }}" class="nav-link-custom">
            <i class="fas fa-file-import me-2"></i> Importar Datos
        </a>
        <a href="{{ route('admin.users') }}" class="nav-link-custom">
            <i class="fas fa-users-cog me-2"></i> Gestionar Usuarios
        </a>
        @else
        <a href="{{ route('notas.mis-notas') }}" class="nav-link-custom active">
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
                        <h4 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Mis Notas Académicas</h4>
                        <span class="d-flex align-items-center">
                            <span class="fs-5 fw-bold badge rounded-pill bg-light text-success px-4 py-2">
                                <i class="fas fa-user me-2"></i> 
                                @if($grado)
                                    {{ $grado }} {{ auth()->user()->name }}
                                @else
                                    {{ auth()->user()->name }}
                                @endif
                            </span>
                        </span>
                    </div>
                </div>
                    <div class="card-body">
                        @if($notas->count() > 0)
                            <!-- Resumen de notas -->
                            <div class="row mb-4">
                                                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-muted">Promedio General</h5>
                                        <h3 class="text-success">
                                            {{ number_format($notas->avg('nota_final'), 1) }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-muted">Total de Evaluaciones</h5>
                                            <h3 class="text-success">{{ $notas->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-muted">Aprobadas</h5>
                                            <h3 class="text-success">
                                                {{ $notas->where('nota_final', '>=', 60)->count() }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-muted">Reprobadas</h5>
                                            <h3 class="text-danger">
                                                {{ $notas->where('nota_final', '<', 60)->count() }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabla de notas -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-success">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Escuela</th>
                                            <th>Grado</th>
                                            <th>Unidad</th>
                                            <th>Escrito</th>
                                            <th>Oral</th>
                                            <th>Físico</th>
                                            <th>Final</th>
                                            <th>Situación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($notas as $nota)
                                            <tr>
                                                <td>{{ $nota->fecha_carga ? $nota->fecha_carga->format('d/m/Y') : '-' }}</td>
                                                <td>
                                                    @if($nota->escuela == 1)
                                                        <span class="badge bg-primary">Esucar</span>
                                                    @elseif($nota->escuela == 2)
                                                        <span class="badge bg-info">Acipol</span>
                                                    @endif
                                                </td>
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
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-graduation-cap fa-5x text-muted mb-4" style="opacity: 0.6;"></i>
                                </div>
                                <h4 class="text-muted mb-3">¡Bienvenido a tu Panel Académico!</h4>
                                <p class="text-muted mb-4" style="font-size: 1.1rem;">
                                    Aún no tienes notas registradas en el sistema.
                                </p>
                                <div class="alert alert-info" style="max-width: 500px; margin: 0 auto;">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Información:</strong> Las notas serán cargadas por los administradores del sistema una vez que se realicen las evaluaciones correspondientes.
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('home.index') }}" class="btn btn-outline-success">
                                        <i class="fas fa-home me-2"></i>Volver al Inicio
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
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
</script>
@endsection 