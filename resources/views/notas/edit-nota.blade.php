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
            <div class="col-md-8">
                <div class="card shadow border-success">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Nota</h4>
                            <a href="{{ route('notas.search') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Volver
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted">Información del Alumno</h6>
                                <p><strong>Nombre:</strong> {{ $nota->nombre_estudiante }}</p>
                                <p><strong>Código:</strong> {{ $nota->codigo_funcionario }}</p>
                                <p><strong>Grado:</strong> {{ $nota->grado }}</p>
                                <p><strong>Unidad:</strong> {{ $nota->unidad }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Información de la Nota</h6>
                                <p><strong>Fecha de Carga:</strong> {{ $nota->fecha_carga ? $nota->fecha_carga->format('d/m/Y') : 'No especificada' }}</p>
                                <p><strong>Escuela:</strong> 
                                    @if($nota->escuela == 1)
                                        <span class="badge bg-primary">Esucar</span>
                                    @elseif($nota->escuela == 2)
                                        <span class="badge bg-info">Acipol</span>
                                    @else
                                        <span class="badge bg-secondary">No especificada</span>
                                    @endif
                                </p>
                                <p><strong>Situación:</strong> 
                                    @if($nota->situacion)
                                        <span class="badge bg-{{ strtolower($nota->situacion) == 'aprobado' ? 'success' : 'danger' }}">
                                            {{ $nota->situacion }}
                                        </span>
                                    @else
                                        <span class="text-muted">No especificada</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('notas.update', $nota->id) }}">
                            @csrf
                            @method('POST')
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="nota_1" class="form-label">Nota Escrito</label>
                                        <input type="number" step="0.1" min="0" max="100" class="form-control" 
                                               id="nota_1" name="nota_1" value="{{ $nota->nota_1 }}" placeholder="0-100">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="nota_2" class="form-label">Nota Oral</label>
                                        <input type="number" step="0.1" min="0" max="100" class="form-control" 
                                               id="nota_2" name="nota_2" value="{{ $nota->nota_2 }}" placeholder="0-100">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="nota_3" class="form-label">Nota Físico</label>
                                        <input type="number" step="0.1" min="0" max="100" class="form-control" 
                                               id="nota_3" name="nota_3" value="{{ $nota->nota_3 }}" placeholder="0-100">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="nota_final" class="form-label">Nota Final</label>
                                        <input type="number" step="0.1" min="0" max="100" class="form-control" 
                                               id="nota_final" name="nota_final" value="{{ $nota->nota_final }}" placeholder="0-100">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="situacion" class="form-label">Situación</label>
                                        <select class="form-select" id="situacion" name="situacion">
                                            <option value="">Seleccionar...</option>
                                            <option value="APROBADO" {{ $nota->situacion == 'APROBADO' ? 'selected' : '' }}>APROBADO</option>
                                            <option value="REPROBADO" {{ $nota->situacion == 'REPROBADO' ? 'selected' : '' }}>REPROBADO</option>
                                            <option value="PENDIENTE" {{ $nota->situacion == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="observaciones" class="form-label">Observaciones</label>
                                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3" placeholder="Observaciones adicionales...">{{ $nota->observaciones }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Guardar Cambios
                                </button>
                                <form method="POST" action="{{ route('notas.destroy', $nota->id) }}" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta nota?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash me-2"></i>Eliminar Nota
                                    </button>
                                </form>
                            </div>
                        </form>

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

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
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