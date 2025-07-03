@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Mis Notas</h4>
                    @if(auth()->user()->rol === 'admin')
                        <a href="{{ route('admin.importar') }}" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Importar CSV
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($notas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nota 1</th>
                                        <th>Nota 2</th>
                                        <th>Nota 3</th>
                                        <th>Nota Final</th>
                                        <th>Observaciones</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notas as $nota)
                                        <tr>
                                            <td>
                                                <span class="badge bg-{{ $nota->nota_1 >= 70 ? 'success' : 'danger' }}">
                                                    {{ $nota->nota_1 ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $nota->nota_2 >= 70 ? 'success' : 'danger' }}">
                                                    {{ $nota->nota_2 ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $nota->nota_3 >= 70 ? 'success' : 'danger' }}">
                                                    {{ $nota->nota_3 ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $nota->nota_final >= 70 ? 'success' : 'danger' }} fs-6">
                                                    {{ $nota->nota_final ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ Str::limit($nota->observaciones, 50) ?? 'Sin observaciones' }}
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $nota->updated_at->format('d/m/Y H:i') }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No tienes notas registradas</h5>
                            <p class="text-muted">Las notas aparecerán aquí cuando sean cargadas por el administrador.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 