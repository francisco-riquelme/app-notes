<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home.index');
});

// Rutas de autenticación
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $user = User::where('email', $credentials['email'])->first();
    if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
        Auth::login($user);
        return redirect()->route('home.index');
    }
    return back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (Request $request) {
    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'min:6', 'confirmed'],
    ]);
    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
        'rol' => 'user',
    ]);
    Auth::login($user);
    return redirect()->route('home.index')->with('success', '¡Registro exitoso! Bienvenido al sistema.');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    // Rutas del panel principal
    Route::resource('home', NotaController::class);
    
    // Ruta para búsqueda de alumnos (solo admin)
    Route::get('/notas/buscar', [NotaController::class, 'search'])->name('notas.search');
    
    // Ruta para que usuarios vean sus notas personales
    Route::get('/mis-notas', [NotaController::class, 'misNotas'])->name('notas.mis-notas');
    
    // Rutas para editar notas (solo admin)
    Route::get('/notas/{id}/editar', [NotaController::class, 'editNota'])->name('notas.edit');
    Route::post('/notas/{id}/actualizar', [NotaController::class, 'updateNota'])->name('notas.update');
    Route::delete('/notas/{id}', [NotaController::class, 'destroyNota'])->name('notas.destroy');
    
    // Ruta para importar CSV (solo admin)
    Route::post('/home/importar-csv', [NotaController::class, 'importarCSV'])->name('home.importar-csv');
    Route::get('/admin/importar-home', function () {
        return view('admin.importar-notas');
    })->name('admin.importar-home');

    // Rutas de perfil
    Route::get('/perfil', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/perfil/editar', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/perfil/editar', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Ruta de gestión de usuarios para admin
    Route::get('/admin/usuarios', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/admin/usuarios/{user}/editar', [\App\Http\Controllers\AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/admin/usuarios/{user}/editar', [\App\Http\Controllers\AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/usuarios/{user}', [\App\Http\Controllers\AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});
