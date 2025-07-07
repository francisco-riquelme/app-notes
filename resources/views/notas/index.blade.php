@extends('layouts.app')

<style>
    body {
        overflow-x: hidden;
    }
    .admin-gradient {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #1e40af 100%);
    }
    .admin-btn {
        background-color: #fff !important;
        border-color: #fff !important;
        color: #1e3a8a !important;
        transition: all 0.3s ease;
    }
    .admin-btn:hover {
        background-color: rgba(255, 255, 255, 0.9) !important;
        border-color: #fff !important;
        color: #1e3a8a !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .admin-card {
        border: 2px solid #3b82f6;
    }
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
    .welcome-panel {
        min-height: 80vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        position: relative;
    }
    .welcome-title {
        font-size: 3rem;
        font-weight: bold;
        color: #176a2b;
        letter-spacing: 8px;
        margin-bottom: 1rem;
        text-shadow: 0 2px 8px rgba(67,160,71,0.08);
    }
    .welcome-user {
        font-size: 2rem;
        color: #43a047;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    .welcome-sub {
        font-size: 1.1rem;
        color: #176a2b;
        margin-bottom: 2rem;
        letter-spacing: 1px;
    }
    .welcome-bg {
        position: absolute;
        left: 0; right: 0; top: 0; bottom: 0;
        z-index: 0;
        opacity: 0.13;
        background: url('/img/logo-tic.png') center center no-repeat;
        background-size: 35% auto;
        pointer-events: none;
        filter: blur(0.5px) grayscale(0.2);
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

@section('content')
<button class="sidebar-toggler" id="sidebarToggle"><i class="fas fa-bars"></i></button>
<div class="sidebar d-flex flex-column justify-content-between" id="sidebarMenu">
    <div>
        <div class="user-name mb-4">
            {{ strtoupper(auth()->user()->name) }}
        </div>
        <a href="{{ route('notas.index') }}" class="nav-link-custom">
            <i class="fas fa-book me-2"></i> Notas
        </a>
        @if(auth()->user()->rol === 'admin')
        <a href="{{ route('admin.importar-notas') }}" class="nav-link-custom">
            <i class="fas fa-file-import me-2"></i> Importar Notas
        </a>
        <a href="{{ route('admin.users') }}" class="nav-link-custom">
            <i class="fas fa-users-cog me-2"></i> Gestionar Usuarios
        </a>
        @endif
        <a href="{{ route('profile.show') }}" class="nav-link-custom">
            <i class="fas fa-user-circle me-2"></i> Perfil
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 position-relative">
                <div class="welcome-bg"></div>
                <div class="welcome-panel position-relative">
                    <div class="welcome-title">BIENVENIDO</div>
                    {{-- <div class="welcome-user">"{{ strtoupper(auth()->user()->name) }}"</div> --}}
                    <div class="welcome-sub">SISTEMA DE GESTIÓN DE NOTAS</div>
                    <div class="welcome-sub">DIRECCIÓN DE TECNOLOGÍAS DE LA INFORMACIÓN Y LAS COMUNICACIONES</div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const sidebar = document.getElementById('sidebarMenu');
    const toggleBtn = document.getElementById('sidebarToggle');
    const links = sidebar.querySelectorAll('.nav-link-custom, form button.nav-link-custom');
    // Abrir sidebar con animación al cargar
    window.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            sidebar.classList.add('open');
            // Animación secuencial de links
            links.forEach((link, i) => {
                setTimeout(() => link.classList.add('visible'), 200 + i * 120);
            });
        }, 200);
    });
    // Botón hamburguesa abre/cierra sidebar
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('open');
        if(sidebar.classList.contains('open')) {
            links.forEach((link, i) => {
                setTimeout(() => link.classList.add('visible'), 100 + i * 120);
            });
        } else {
            links.forEach(link => link.classList.remove('visible'));
        }
    });
</script>
@endsection 