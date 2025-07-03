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
            'archivo_csv' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        $archivo = $request->file('archivo_csv');
        $ruta = $archivo->storeAs('csv', 'notas_' . time() . '.csv');

        // Leer el archivo CSV
        $handle = fopen(storage_path('app/' . $ruta), 'r');
        $encabezados = fgetcsv($handle); // Saltar la primera línea (encabezados)

        $importados = 0;
        $errores = [];

        while (($datos = fgetcsv($handle)) !== false) {
            try {
                // Asumiendo que el CSV tiene: user_id, nota_1, nota_2, nota_3, nota_final, observaciones
                $nota = [
                    'user_id' => $datos[0],
                    'nota_1' => $datos[1] ?: null,
                    'nota_2' => $datos[2] ?: null,
                    'nota_3' => $datos[3] ?: null,
                    'nota_final' => $datos[4] ?: null,
                    'observaciones' => $datos[5] ?? null
                ];

                // Verificar si el usuario existe
                if (!User::find($nota['user_id'])) {
                    $errores[] = "Usuario ID {$nota['user_id']} no existe";
                    continue;
                }

                // Crear o actualizar la nota
                Nota::updateOrCreate(
                    ['user_id' => $nota['user_id']],
                    $nota
                );

                $importados++;
            } catch (\Exception $e) {
                $errores[] = "Error en línea: " . implode(',', $datos) . " - " . $e->getMessage();
            }
        }

        fclose($handle);

        $mensaje = "Se importaron {$importados} notas exitosamente.";
        if (!empty($errores)) {
            $mensaje .= " Errores: " . implode(', ', $errores);
        }

        return redirect()->back()->with('success', $mensaje);
    }
}
