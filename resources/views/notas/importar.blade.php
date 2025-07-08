@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Importar Datos desde CSV</h4>
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

                    <form action="{{ route('home.importar-csv') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="archivo_csv" class="form-label">Seleccionar archivo CSV</label>
                            <input type="file" class="form-control @error('archivo_csv') is-invalid @enderror" 
                                   id="archivo_csv" name="archivo_csv" accept=".csv,.txt" required>
                            @error('archivo_csv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Formato del CSV:</h6>
                            <p class="mb-1">El archivo debe tener el siguiente formato:</p>
                            <code>user_id,nota_1,nota_2,nota_3,nota_final,observaciones</code>
                            <br><br>
                            <strong>Ejemplo:</strong><br>
                            <code>1,85,90,88,87.7,Excelente trabajo</code><br>
                            <code>2,75,80,82,79.0,Necesita mejorar</code>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('home.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Importar Datos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 