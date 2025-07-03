<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    // Mostrar perfil
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    // Formulario de edición
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Guardar cambios
    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'password' => 'nullable|min:6|confirmed',
        ]);
        if ($data['password']) {
            $user->password = bcrypt($data['password']);
        }
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->telefono = $data['telefono'] ?? null;
        $user->direccion = $data['direccion'] ?? null;
        $user->save();
        return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente');
    }
}
