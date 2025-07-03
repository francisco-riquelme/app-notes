@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100" style="background: linear-gradient(135deg, #e9f5ee 0%, #b7e4c7 100%);">
    <div class="row w-100 justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-success">
                <div class="card-header text-center bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Registro de Usuario</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ url('/register') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre completo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror border-success" id="name" name="name" value="{{ old('name') }}" required autofocus>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror border-success" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror border-success" id="password" name="password" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control border-success" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg shadow-sm"><i class="fas fa-user-plus me-1"></i>Registrarse</button>
                        </div>
                    </form>
                    <div class="mt-4 text-center">
                        <span>¿Ya tienes cuenta?</span>
                        <a href="{{ url('/login') }}" class="text-success fw-bold">Inicia sesión aquí</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 