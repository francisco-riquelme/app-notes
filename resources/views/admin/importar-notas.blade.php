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
        <a href="{{ route('admin.importar-home') }}" class="nav-link-custom active">
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
                            <h4 class="mb-0"><i class="fas fa-upload me-2"></i>Cargar Registros del Panel</h4>
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show mb-0" role="alert" style="background: rgba(255,255,255,0.9); color: #198754; border: none;">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert" style="background: rgba(255,255,255,0.9); color: #dc3545; border: none;">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Instrucciones:</strong> Selecciona un archivo CSV con el formato: user_id, nota_1, nota_2, nota_3, nota_final, observaciones
                        </div>
                        
                        <div class="mb-5 text-center">
                            <h5 class="text-success"><i class="fas fa-school me-2"></i>Escuela de Suboficiales de Carabineros de Chile</h5>
                            <div class="alert alert-info text-start">
                                <strong>Instrucciones Formato 1:</strong><br>
                                El archivo CSV debe tener las siguientes columnas (en este orden):<br>
                                <code>codigo_funcionario, grado, nombre, unidad, escrito, oral, fisico, final, situacion, id_posicion, grupo</code><br>
                            </div>
                            <form action="{{ route('home.importar-csv') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                                @csrf
                                <input type="hidden" name="escuela" value="1">
                                <input type="hidden" name="formato" value="1">
                                <div class="mb-3">
                                    <input type="file" name="archivo" class="form-control" accept=".csv,.txt" required>
                                </div>
                                <button type="submit" class="btn btn-success" style="background-color: #43a047; border-color: #388e3c;">
                                    <i class="fas fa-file-import"></i> Importar Datos Esucar Formato 1
                                </button>
                            </form>
                            <div class="alert alert-info text-start mt-4">
                                <strong>Instrucciones Formato 2:</strong><br>
                                El archivo CSV debe tener las siguientes columnas (en este orden):<br>
                                <code>codigo_funcionario, grado (número), nombre, unidad, sede, sexo, grado (texto), final, posicion</code><br>
                            </div>
                            <form action="{{ route('home.importar-csv') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                                @csrf
                                <input type="hidden" name="escuela" value="1">
                                <input type="hidden" name="formato" value="2">
                                <div class="mb-3">
                                    <input type="file" name="archivo" class="form-control" accept=".csv,.txt" required>
                                </div>
                                <button type="submit" class="btn btn-success" style="background-color: #43a047; border-color: #388e3c;">
                                    <i class="fas fa-file-import"></i> Importar Datos Esucar Formato 2
                                </button>
                            </form>
                        </div>
                        
                        <hr>
                        
                        <div class="mb-3 text-center">
                            <h5 class="text-success"><i class="fas fa-school me-2"></i>Academia de Ciencias Policiales de Carabineros de Chile</h5>
                            <div class="alert alert-info text-start">
                                <strong>Instrucciones:</strong><br>
                                El archivo CSV debe tener las siguientes columnas (en este orden):<br>
                                <code>codigo_funcionario, grado, nombre, unidad, escrito, oral, fisico, final, situacion, id_posicion, tipo</code><br>
                                <br>
                                <strong>Importante:</strong> El campo <b>tipo</b> debe ser:
                                <ul>
                                    <li><b>1</b>: Intendencia</li>
                                    <li><b>2</b>: Orden y Seguridad</li>
                                </ul>
                            </div>
                            <form action="{{ route('home.importar-csv') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="escuela" value="2">
                                <div class="mb-3">
                                    <input type="file" name="archivo" class="form-control" accept=".csv,.txt" required>
                                </div>
                                <button type="submit" class="btn btn-success" style="background-color: #43a047; border-color: #388e3c;">
                                    <i class="fas fa-file-import"></i> Importar Datos Acipol
                                </button>
                            </form>
                        </div>
                        

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