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
     * Muestra las notas del usuario logueado
     */
    public function index()
    {
        // Obtener las notas del usuario actual
        $notas = Nota::where('user_id', auth()->id())->get();
        return view('notas.index', compact('notas'));
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
        return redirect()->route('notas.index')->with('success', 'Nota creada exitosamente');
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
        return redirect()->route('notas.index')->with('success', 'Nota actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $nota = Nota::findOrFail($id);
        $nota->delete();
        return redirect()->route('notas.index')->with('success', 'Nota eliminada exitosamente');
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
        $encabezados = fgetcsv($handle); // Saltar la primera línea (encabezados)

        $importados = 0;
        $errores = [];

        while (($datos = fgetcsv($handle)) !== false) {
            try {
                // Determinar formato y mapear columnas
                if ($escuela == 1 && $formato == 1) {
                    // Formato 1 Escuela 1
                    // codigo_funcionario, grado, nombre, unidad, escrito, oral, fisico, final, situacion, id_posicion, grupo
                    $nota = [
                        'user_id' => null, // No se asocia usuario
                        'nota_1' => $datos[4] ?: null,
                        'nota_2' => $datos[5] ?: null,
                        'nota_3' => $datos[6] ?: null,
                        'nota_final' => $datos[7] ?: null,
                        'observaciones' => $datos[8] ?? null,
                        'escuela_id' => $escuela_id,
                        'escalafon_id' => null // No hay tipo en este formato
                    ];
                } elseif ($escuela == 1 && $formato == 2) {
                    // Formato 2 Escuela 1
                    // codigo_funcionario, grado (número), nombre, unidad, sede, sexo, grado (texto), final, posicion
                    $nota = [
                        'user_id' => null,
                        'nota_1' => null,
                        'nota_2' => null,
                        'nota_3' => null,
                        'nota_final' => $datos[7] ?: null,
                        'observaciones' => null,
                        'escuela_id' => $escuela_id,
                        'escalafon_id' => null
                    ];
                } elseif ($escuela == 2) {
                    // Formato Escuela 2
                    // codigo_funcionario, grado, nombre, unidad, escrito, oral, fisico, final, situacion, id_posicion, tipo
                    $tipo = isset($datos[11]) ? $datos[11] : null;
                    // Buscar el id de escalafon
                    $escalafon_id = null;
                    if ($tipo == 1 || $tipo == '1') {
                        $escalafon_id = 1;
                    } elseif ($tipo == 2 || $tipo == '2') {
                        $escalafon_id = 2;
                    }
                    $nota = [
                        'user_id' => null,
                        'nota_1' => $datos[4] ?: null,
                        'nota_2' => $datos[5] ?: null,
                        'nota_3' => $datos[6] ?: null,
                        'nota_final' => $datos[7] ?: null,
                        'observaciones' => $datos[8] ?? null,
                        'escuela_id' => $escuela_id,
                        'escalafon_id' => $escalafon_id
                    ];
                } else {
                    $errores[] = "Formato o escuela no reconocido";
                    continue;
                }

                // Crear la nota
                \App\Models\Nota::create($nota);
                $importados++;
            } catch (\Exception $e) {
                $errores[] = "Error en línea: " . implode(',', $datos) . " - " . $e->getMessage();
            }
        }

        fclose($handle);

        $mensaje = "Se importaron {$importados} notas de la escuela {$escuela} exitosamente.";
        if (!empty($errores)) {
            $mensaje .= " Errores: " . implode(', ', $errores);
        }

        return redirect()->back()->with('success', $mensaje);
    }
}
