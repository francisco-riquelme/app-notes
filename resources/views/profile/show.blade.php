@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow border-success">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-user-circle me-2"></i>Mi Perfil</h4>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-light btn-sm"><i class="fas fa-edit"></i> Editar</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Nombre</dt>
                        <dd class="col-sm-8">{{ $user->name }}</dd>
                        <dt class="col-sm-4">Correo electrónico</dt>
                        <dd class="col-sm-8">{{ $user->email }}</dd>
                        <dt class="col-sm-4">Teléfono</dt>
                        <dd class="col-sm-8">{{ $user->telefono ?? 'No registrado' }}</dd>
                        <dt class="col-sm-4">Dirección</dt>
                        <dd class="col-sm-8">{{ $user->direccion ?? 'No registrada' }}</dd>
                        <dt class="col-sm-4">Rol</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-success text-white text-uppercase">{{ $user->rol }}</span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 