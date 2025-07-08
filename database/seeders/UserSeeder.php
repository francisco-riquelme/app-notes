<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuarios de ejemplo para Esucar (Escuela 1) - Formato 1
        $usuariosEsucarFormato1 = [
            [
                'name' => 'Juan Pérez González',
                'email' => 'juan.perez@esucar.cl',
                'codigo_funcionario' => '12345',
                'escuela' => 1,
                'rol' => 'user',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'María Rodríguez Silva',
                'email' => 'maria.rodriguez@esucar.cl',
                'codigo_funcionario' => '12346',
                'escuela' => 1,
                'rol' => 'user',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Carlos López Mendoza',
                'email' => 'carlos.lopez@esucar.cl',
                'codigo_funcionario' => '12347',
                'escuela' => 1,
                'rol' => 'user',
                'password' => Hash::make('123456'),
            ],
        ];

        // Usuarios de ejemplo para Esucar (Escuela 1) - Formato 2
        $usuariosEsucarFormato2 = [
            [
                'name' => 'Luis Fernández Castro',
                'email' => 'luis.fernandez@esucar.cl',
                'codigo_funcionario' => '23456',
                'escuela' => 1,
                'rol' => 'user',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Carmen Silva Vargas',
                'email' => 'carmen.silva@esucar.cl',
                'codigo_funcionario' => '23457',
                'escuela' => 1,
                'rol' => 'user',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Roberto Díaz Morales',
                'email' => 'roberto.diaz@esucar.cl',
                'codigo_funcionario' => '23458',
                'escuela' => 1,
                'rol' => 'user',
                'password' => Hash::make('123456'),
            ],
        ];

        // Usuarios de ejemplo para Acipol (Escuela 2)
        $usuariosAcipol = [
            [
                'name' => 'Diego Morales Ríos',
                'email' => 'diego.morales@acipol.cl',
                'codigo_funcionario' => '34567',
                'escuela' => 2,
                'rol' => 'user',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Valentina Castro Silva',
                'email' => 'valentina.castro@acipol.cl',
                'codigo_funcionario' => '34568',
                'escuela' => 2,
                'rol' => 'user',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Francisco Rojas Mendoza',
                'email' => 'francisco.rojas@acipol.cl',
                'codigo_funcionario' => '34569',
                'escuela' => 2,
                'rol' => 'user',
                'password' => Hash::make('123456'),
            ],
        ];

        // Crear usuarios
        foreach ($usuariosEsucarFormato1 as $usuario) {
            User::create($usuario);
        }

        foreach ($usuariosEsucarFormato2 as $usuario) {
            User::create($usuario);
        }

        foreach ($usuariosAcipol as $usuario) {
            User::create($usuario);
        }

        $this->command->info('Usuarios de ejemplo creados exitosamente.');
    }
}
