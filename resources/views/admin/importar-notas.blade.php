@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-success">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-upload me-2"></i>Cargar Registros de Notas</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Instrucciones:</strong> Selecciona un archivo CSV con el formato: user_id, nota_1, nota_2, nota_3, nota_final, observaciones
                    </div>
                    
                    <div class="mb-5 text-center">
                        <h5 class="text-success"><i class="fas fa-school me-2"></i>Escuela 1</h5>
                        <form action="{{ route('notas.importar-csv') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="escuela" value="1">
                            <div class="mb-3">
                                <input type="file" name="archivo" class="form-control" accept=".csv,.txt" required>
                            </div>
                            <button type="submit" class="btn btn-success" style="background-color: #43a047; border-color: #388e3c;">
                                <i class="fas fa-file-import"></i> Importar Notas Escuela 1
                            </button>
                        </form>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3 text-center">
                        <h5 class="text-primary"><i class="fas fa-school me-2"></i>Escuela 2</h5>
                        <form action="{{ route('notas.importar-csv') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="escuela" value="2">
                            <div class="mb-3">
                                <input type="file" name="archivo" class="form-control" accept=".csv,.txt" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-import"></i> Importar Notas Escuela 2
                            </button>
                        </form>
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
@endsection 