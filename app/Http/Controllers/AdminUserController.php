<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    // Mostrar todos los usuarios
    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');
        $usuarios = User::when($busqueda, function($query, $busqueda) {
            return $query->where('name', 'like', "%$busqueda%");
        })->get();
        // Mover el usuario admin autenticado al principio
        if (auth()->check()) {
            $admin = $usuarios->where('id', auth()->id())->first();
            if ($admin) {
                $usuarios = $usuarios->reject(fn($u) => $u->id === $admin->id);
                $usuarios->prepend($admin);
            }
        }
        return view('admin.users.index', compact('usuarios', 'busqueda'));
    }

    // Formulario de edición
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Guardar cambios
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'rol' => 'required|in:admin,user',
            'password' => 'nullable|min:6|confirmed',
        ]);
        if ($data['password']) {
            $user->password = bcrypt($data['password']);
        }
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->telefono = $data['telefono'] ?? null;
        $user->direccion = $data['direccion'] ?? null;
        $user->rol = $data['rol'];
        $user->save();
        return redirect()->route('admin.users')->with('success', 'Usuario actualizado correctamente');
    }

    // Eliminar usuario
    public function destroy(User $user)
    {
        // No permitir que el admin se elimine a sí mismo
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'No puedes eliminar tu propia cuenta');
        }

        // Verificar que sea admin
        if (auth()->user()->rol !== 'admin') {
            return redirect()->route('admin.users')->with('error', 'No tienes permisos para realizar esta acción');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Usuario eliminado correctamente');
    }
}
