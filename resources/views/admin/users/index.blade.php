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
                    <div class="mb-5 text-center">
                        <h5>Escuela 1</h5>
                        <form action="{{ route('notas.importar', ['escuela' => 1]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="archivo" class="form-control mb-3" required>
                            <button type="submit" class="btn btn-success" style="background-color: #43a047; border-color: #388e3c;">
                                <i class="fas fa-file-import"></i> Importar Notas
                            </button>
                        </form>
                    </div>
                    <div class="mb-3 text-center">
                        <h5>Escuela 2</h5>
                        <form action="{{ route('notas.importar', ['escuela' => 2]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="archivo" class="form-control mb-3" required>
                            <button type="submit" class="btn btn-success" style="background-color: #43a047; border-color: #388e3c;">
                                <i class="fas fa-file-import"></i> Importar Notas
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 