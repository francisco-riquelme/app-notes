<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .navbar .btn-outline-light {
            border-width: 1px;
            transition: all 0.3s ease;
        }
        .navbar .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-1px);
        }
        .navbar .btn-outline-light:active {
            transform: translateY(0);
        }
        .navbar-nav .nav-item {
            display: flex;
            align-items: center;
        }
        .navbar-brand {
            margin-right: 0;
            padding-left: 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}" style="margin-left: 0; padding-left: 0;">
                <i class="fas fa-graduation-cap"></i> App Gestión de Notas
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link">Hola, <strong>{{ auth()->user()->name }}</strong></span>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link text-success-emphasis" href="{{ route('login') }}">Iniciar sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-success-emphasis" href="{{ route('register') }}">Registrarse</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <main>
        @if(session('success'))
            <div id="toast-success" class="toast align-items-center border-0 position-fixed top-0 end-0 m-4 show" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999; min-width: 340px; background-color: #43d39e; color: #fff; font-size: 1.25rem; border-radius: 0; box-shadow: 0 4px 16px rgba(67,211,158,0.15);">
                <div class="d-flex">
                    <div class="toast-body w-100">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    var toast = document.getElementById('toast-success');
                    if (toast) {
                        var bsToast = bootstrap.Toast.getOrCreateInstance(toast);
                        bsToast.hide();
                    }
                }, 3000);
            </script>
        @endif
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 