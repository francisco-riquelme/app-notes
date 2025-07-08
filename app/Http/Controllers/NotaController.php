<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class NotaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Muestra las notas del usuario logueado o todas las notas para admin
     */
    public function index(Request $request)
    {
        // Para todos los usuarios: mostrar la página de bienvenida
        return view('notas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nota_1' => 'nullable|numeric|min:0|max:100',
            'nota_2' => 'nullable|numeric|min:0|max:100',
            'nota_3' => 'nullable|numeric|min:0|max:100',
            'nota_final' => 'nullable|numeric|min:0|max:100',
            'observaciones' => 'nullable|string'
        ]);

        Nota::create($request->all());
        return redirect()->route('home.index')->with('success', 'Nota creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $nota = Nota::findOrFail($id);
        return view('notas.show', compact('nota'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $nota = Nota::findOrFail($id);
        return view('notas.edit', compact('nota'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nota_1' => 'nullable|numeric|min:0|max:100',
            'nota_2' => 'nullable|numeric|min:0|max:100',
            'nota_3' => 'nullable|numeric|min:0|max:100',
            'nota_final' => 'nullable|numeric|min:0|max:100',
            'observaciones' => 'nullable|string'
        ]);

        $nota = Nota::findOrFail($id);
        $nota->update($request->all());
        return redirect()->route('home.index')->with('success', 'Nota actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $nota = Nota::findOrFail($id);
        $nota->delete();
        return redirect()->route('home.index')->with('success', 'Nota eliminada exitosamente');
    }

    /**
     * Importar notas desde CSV (solo admin)
     */
    public function importarCSV(Request $request)
    {
        // Verificar que sea admin
        if (auth()->user()->rol !== 'admin') {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción');
        }

        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt|max:2048',
            'escuela' => 'required|in:1,2',
            'formato' => 'nullable|in:1,2'
        ]);

        $archivo = $request->file('archivo');
        $escuela = $request->input('escuela');
        $formato = $request->input('formato');
        $ruta = $archivo->storeAs('csv', 'notas_escuela_' . $escuela . '_' . time() . '.csv');

        // Obtener el id de la escuela
        $escuela_id = $escuela;

        // Leer el archivo CSV
        $handle = fopen(storage_path('app/' . $ruta), 'r');
        $encabezados = fgetcsv($handle); // Obtener la primera línea (encabezados)

        // Validar formato según la escuela y formato
        $formatoValido = $this->validarFormatoCSV($encabezados, $escuela, $formato);
        if (!$formatoValido['valido']) {
            fclose($handle);
            return redirect()->back()->with('error', 'Formato de archivo incorrecto. ' . $formatoValido['mensaje']);
        }

        $importados = 0;
        $errores = [];
        $fechaCarga = now()->toDateString();

        while (($datos = fgetcsv($handle)) !== false) {
            try {
                // Buscar usuario por código de funcionario
                $codigo_funcionario = $datos[0] ?? null;
                $usuario = null;
                
                if ($codigo_funcionario) {
                    $usuario = \App\Models\User::where('codigo_funcionario', $codigo_funcionario)->first();
                }
                
                // Determinar formato y mapear columnas
                if ($escuela == 1 && $formato == 1) {
                    // Formato 1 Escuela 1: codigo_funcionario, grado, nombre, unidad, escrito, oral, fisico, final, situacion, id_posicion, grupo
                    $nota = [
                        'user_id' => $usuario ? $usuario->id : null,
                        'codigo_funcionario' => $datos[0] ?? null,
                        'nombre_estudiante' => $datos[2] ?? null,
                        'grado' => $datos[1] ?? null,
                        'unidad' => $datos[3] ?? null,
                        'situacion' => $datos[8] ?? null,
                        'id_posicion' => $datos[9] ?? null,
                        'grupo' => $datos[10] ?? null,
                        'nota_1' => $datos[4] ?: null,
                        'nota_2' => $datos[5] ?: null,
                        'nota_3' => $datos[6] ?: null,
                        'nota_final' => $datos[7] ?: null,
                        'observaciones' => $datos[8] ?? null,
                        'escuela' => $escuela_id,
                        'escalafon_id' => null,
                        'fecha_carga' => $fechaCarga,
                        'admin_id' => auth()->id()
                    ];
                } elseif ($escuela == 1 && $formato == 2) {
                    // Formato 2 Escuela 1: codigo_funcionario, grado (número), nombre, unidad, sede, sexo, grado (texto), final, posicion
                    $nota = [
                        'user_id' => $usuario ? $usuario->id : null,
                        'codigo_funcionario' => $datos[0] ?? null,
                        'nombre_estudiante' => $datos[2] ?? null,
                        'grado' => $datos[6] ?? null,
                        'unidad' => $datos[3] ?? null,
                        'sede' => $datos[4] ?? null,
                        'sexo' => $datos[5] ?? null,
                        'id_posicion' => $datos[8] ?? null,
                        'nota_1' => null,
                        'nota_2' => null,
                        'nota_3' => null,
                        'nota_final' => $datos[7] ?: null,
                        'observaciones' => null,
                        'escuela' => $escuela_id,
                        'escalafon_id' => null,
                        'fecha_carga' => $fechaCarga,
                        'admin_id' => auth()->id()
                    ];
                } elseif ($escuela == 2) {
                    // Formato Escuela 2: codigo_funcionario, grado, nombre, unidad, escrito, oral, fisico, final, situacion, id_posicion, tipo
                    $tipo = isset($datos[10]) ? $datos[10] : null;
                    // Buscar el id de escalafon
                    $escalafon_id = null;
                    if ($tipo == 1 || $tipo == '1') {
                        $escalafon_id = 1;
                    } elseif ($tipo == 2 || $tipo == '2') {
                        $escalafon_id = 2;
                    }
                    $nota = [
                        'user_id' => $usuario ? $usuario->id : null,
                        'codigo_funcionario' => $datos[0] ?? null,
                        'nombre_estudiante' => $datos[2] ?? null,
                        'grado' => $datos[1] ?? null,
                        'unidad' => $datos[3] ?? null,
                        'situacion' => $datos[8] ?? null,
                        'id_posicion' => $datos[9] ?? null,
                        'nota_1' => $datos[4] ?: null,
                        'nota_2' => $datos[5] ?: null,
                        'nota_3' => $datos[6] ?: null,
                        'nota_final' => $datos[7] ?: null,
                        'observaciones' => $datos[8] ?? null,
                        'escuela' => $escuela_id,
                        'escalafon_id' => $escalafon_id,
                        'fecha_carga' => $fechaCarga,
                        'admin_id' => auth()->id()
                    ];
                } else {
                    $errores[] = "Formato o escuela no reconocido";
                    continue;
                }

                // Verificar si ya existe una nota para este usuario con los mismos datos
                $notaExistente = \App\Models\Nota::where('user_id', $nota['user_id'])
                    ->where('grado', $nota['grado'])
                    ->where('unidad', $nota['unidad'])
                    ->where('fecha_carga', $nota['fecha_carga'])
                    ->first();
                
                if (!$notaExistente) {
                    // Crear la nota solo si no existe
                    \App\Models\Nota::create($nota);
                    $importados++;
                }
                // Si existe, simplemente la omitimos (no es un error)
            } catch (\Exception $e) {
                $errores[] = "Error en línea: " . implode(',', $datos) . " - " . $e->getMessage();
            }
        }

        fclose($handle);

        // Preparar mensaje de resultado
        if ($importados > 0) {
            $mensaje = "Se importaron {$importados} registros correctamente";
            if (!empty($errores)) {
                $mensaje .= " (Algunos registros no se pudieron procesar)";
            }
            return redirect()->back()->with('success', $mensaje);
        } else {
            return redirect()->back()->with('error', 'No se pudieron cargar datos. Verifique el formato del archivo.');
        }
    }

    /**
     * Validar formato del CSV según escuela y formato
     */
    private function validarFormatoCSV($encabezados, $escuela, $formato)
    {
        $encabezadosEsperados = [];
        
        if ($escuela == 1 && $formato == 1) {
            $encabezadosEsperados = ['codigo_funcionario', 'grado', 'nombre', 'unidad', 'escrito', 'oral', 'fisico', 'final', 'situacion', 'id_posicion', 'grupo'];
        } elseif ($escuela == 1 && $formato == 2) {
            $encabezadosEsperados = ['codigo_funcionario', 'grado', 'nombre', 'unidad', 'sede', 'sexo', 'grado', 'final', 'posicion'];
        } elseif ($escuela == 2) {
            $encabezadosEsperados = ['codigo_funcionario', 'grado', 'nombre', 'unidad', 'escrito', 'oral', 'fisico', 'final', 'situacion', 'id_posicion', 'tipo'];
        }

        if (count($encabezados) !== count($encabezadosEsperados)) {
            return [
                'valido' => false,
                'mensaje' => 'Número de columnas incorrecto. Esperado: ' . count($encabezadosEsperados) . ', Encontrado: ' . count($encabezados)
            ];
        }

        // Verificar que los encabezados coincidan
        for ($i = 0; $i < count($encabezadosEsperados); $i++) {
            if (strtolower(trim($encabezados[$i])) !== strtolower(trim($encabezadosEsperados[$i]))) {
                return [
                    'valido' => false,
                    'mensaje' => 'Encabezado incorrecto en columna ' . ($i + 1) . '. Esperado: ' . $encabezadosEsperados[$i] . ', Encontrado: ' . $encabezados[$i]
                ];
            }
        }

        return ['valido' => true, 'mensaje' => 'Formato válido'];
    }

    /**
     * Búsqueda de alumnos (solo admin)
     */
    public function search(Request $request)
    {
        // Verificar que sea admin
        if (auth()->user()->rol !== 'admin') {
            return redirect()->route('home.index')->with('error', 'No tienes permisos para acceder a esta página');
        }

        // Buscar solo usuarios actuales (rol user)
        $usuariosQuery = \App\Models\User::where('rol', 'user');
        if ($request->filled('escuela')) {
            $usuariosQuery->where('escuela', $request->escuela);
        }
        if ($request->filled('busqueda')) {
            $busqueda = $request->busqueda;
            $usuariosQuery->where(function($q) use ($busqueda) {
                $q->where('name', 'like', "%{$busqueda}%")
                  ->orWhere('codigo_funcionario', 'like', "%{$busqueda}%");
            });
        }
        $usuarios = $usuariosQuery->orderBy('name')->get();

        // Notas filtradas (opcional, si quieres mostrar notas también)
        $notas = collect();
        if ($usuarios->count() > 0) {
            $userIds = $usuarios->pluck('id');
            $notasQuery = \App\Models\Nota::whereIn('user_id', $userIds);
            if ($request->filled('anio')) {
                $notasQuery->whereYear('fecha_carga', $request->anio);
            }
            $notas = $notasQuery->orderBy('fecha_carga', 'desc')->get();
        }

        return view('notas.search', compact('usuarios', 'notas'));
    }

    /**
     * Mostrar notas personales del usuario
     */
    public function misNotas()
    {
        // Obtener notas del usuario logueado
        $notas = Nota::where('user_id', auth()->id())->orderBy('fecha_carga', 'desc')->get();
        
        // Obtener el grado de la primera nota para mostrar en el header
        $grado = $notas->first() ? $notas->first()->grado : null;
        
        return view('notas.student', compact('notas', 'grado'));
    }

    /**
     * Editar nota (solo admin)
     */
    public function editNota($id)
    {
        // Verificar que sea admin
        if (auth()->user()->rol !== 'admin') {
            return redirect()->route('home.index')->with('error', 'No tienes permisos para realizar esta acción');
        }

        $nota = Nota::findOrFail($id);
        return view('notas.edit-nota', compact('nota'));
    }

    /**
     * Actualizar nota (solo admin)
     */
    public function updateNota(Request $request, $id)
    {
        // Verificar que sea admin
        if (auth()->user()->rol !== 'admin') {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción');
        }

        $request->validate([
            'nota_1' => 'nullable|numeric|min:0|max:100',
            'nota_2' => 'nullable|numeric|min:0|max:100',
            'nota_3' => 'nullable|numeric|min:0|max:100',
            'nota_final' => 'nullable|numeric|min:0|max:100',
            'situacion' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string'
        ]);

        $nota = Nota::findOrFail($id);
        $nota->update($request->all());

        return redirect()->route('notas.search')->with('success', 'Nota actualizada correctamente');
    }

    /**
     * Eliminar nota (solo admin)
     */
    public function destroyNota($id)
    {
        // Verificar que sea admin
        if (auth()->user()->rol !== 'admin') {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción');
        }

        $nota = Nota::findOrFail($id);
        $nota->delete();

        return redirect()->route('notas.search')->with('success', 'Nota eliminada correctamente');
    }
}
