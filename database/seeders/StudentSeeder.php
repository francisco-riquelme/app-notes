<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Crear usuarios estudiantes de ejemplo
        $estudiantes = [
            [
                'name' => 'Juan Pérez González',
                'email' => 'juan.perez@demo.com',
                'password' => bcrypt('123456'),
                'rol' => 'user',
                'telefono' => '666666666',
                'direccion' => '1ra Comisaría Santiago Centro',
            ],
            [
                'name' => 'María Rodríguez Silva',
                'email' => 'maria.rodriguez@demo.com',
                'password' => bcrypt('123456'),
                'rol' => 'user',
                'telefono' => '777777777',
                'direccion' => '2da Comisaría Providencia',
            ],
            [
                'name' => 'Diego Morales Ríos',
                'email' => 'diego.morales@demo.com',
                'password' => bcrypt('123456'),
                'rol' => 'user',
                'telefono' => '888888888',
                'direccion' => 'Academia de Ciencias Policiales',
            ],
            [
                'name' => 'Carlos López Mendoza',
                'email' => 'carlos.lopez@demo.com',
                'password' => bcrypt('123456'),
                'rol' => 'user',
                'telefono' => '999999999',
                'direccion' => '3ra Comisaría Las Condes',
            ],
            [
                'name' => 'Ana Martínez Torres',
                'email' => 'ana.martinez@demo.com',
                'password' => bcrypt('123456'),
                'rol' => 'user',
                'telefono' => '101010101',
                'direccion' => '4ta Comisaría Ñuñoa',
            ],
        ];

        foreach ($estudiantes as $estudiante) {
            User::firstOrCreate(
                ['email' => $estudiante['email']],
                $estudiante
            );
        }
    }
}
