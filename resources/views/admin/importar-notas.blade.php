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
                        <h5 class="text-success"><i class="fas fa-school me-2"></i>Escuela de Suboficiales de Carabineros de Chile</h5>
                        <div class="alert alert-info text-start">
                            <strong>Instrucciones Formato 1:</strong><br>
                            El archivo CSV debe tener las siguientes columnas (en este orden):<br>
                            <code>codigo_funcionario, grado, nombre, unidad, escrito, oral, fisico, final, situacion, id_posicion, grupo</code><br>
                        </div>
                        <form action="{{ route('notas.importar-csv') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                            @csrf
                            <input type="hidden" name="escuela" value="1">
                            <input type="hidden" name="formato" value="1">
                            <div class="mb-3">
                                <input type="file" name="archivo" class="form-control" accept=".csv,.txt" required>
                            </div>
                            <button type="submit" class="btn btn-success" style="background-color: #43a047; border-color: #388e3c;">
                                <i class="fas fa-file-import"></i> Importar Notas Formato 1
                            </button>
                        </form>
                        <div class="alert alert-info text-start mt-4">
                            <strong>Instrucciones Formato 2:</strong><br>
                            El archivo CSV debe tener las siguientes columnas (en este orden):<br>
                            <code>codigo_funcionario, grado (número), nombre, unidad, sede, sexo, grado (texto), final, posicion</code><br>
                        </div>
                        <form action="{{ route('notas.importar-csv') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                            @csrf
                            <input type="hidden" name="escuela" value="1">
                            <input type="hidden" name="formato" value="2">
                            <div class="mb-3">
                                <input type="file" name="archivo" class="form-control" accept=".csv,.txt" required>
                            </div>
                            <button type="submit" class="btn btn-success" style="background-color: #43a047; border-color: #388e3c;">
                                <i class="fas fa-file-import"></i> Importar Notas Formato 2
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
                        <form action="{{ route('notas.importar-csv') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="escuela" value="2">
                            <div class="mb-3">
                                <input type="file" name="archivo" class="form-control" accept=".csv,.txt" required>
                            </div>
                            <button type="submit" class="btn btn-success" style="background-color: #43a047; border-color: #388e3c;">
                                <i class="fas fa-file-import"></i> Importar Notas Academia de Ciencias Policiales
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