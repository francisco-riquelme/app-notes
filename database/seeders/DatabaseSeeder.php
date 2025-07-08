<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\User::create([
            'name' => 'Admin Principal',
            'email' => 'admin1@demo.com',
            'password' => bcrypt('123456'),
            'rol' => 'admin',
            'telefono' => '111111111',
            'direccion' => 'Dirección Admin 1',
        ]);
        \App\Models\User::create([
            'name' => 'Admin Secundario',
            'email' => 'admin2@demo.com',
            'password' => bcrypt('123456'),
            'rol' => 'admin',
            'telefono' => '222222222',
            'direccion' => 'Dirección Admin 2',
        ]);
        \App\Models\User::create([
            'name' => 'Usuario Prueba 1',
            'email' => 'user1@demo.com',
            'password' => bcrypt('123456'),
            'rol' => 'user',
            'telefono' => '333333333',
            'direccion' => 'Dirección User 1',
        ]);
        \App\Models\User::create([
            'name' => 'Usuario Prueba 2',
            'email' => 'user2@demo.com',
            'password' => bcrypt('123456'),
            'rol' => 'user',
            'telefono' => '444444444',
            'direccion' => 'Dirección User 2',
        ]);
        \App\Models\User::create([
            'name' => 'Usuario Prueba 3',
            'email' => 'user3@demo.com',
            'password' => bcrypt('123456'),
            'rol' => 'user',
            'telefono' => '555555555',
            'direccion' => 'Dirección User 3',
        ]);

        // Crear usuarios estudiantes de ejemplo
        \App\Models\User::create([
            'name' => 'Juan Pérez González',
            'email' => 'juan.perez@demo.com',
            'password' => bcrypt('123456'),
            'rol' => 'user',
            'telefono' => '666666666',
            'direccion' => '1ra Comisaría Santiago Centro',
        ]);

        \App\Models\User::create([
            'name' => 'María Rodríguez Silva',
            'email' => 'maria.rodriguez@demo.com',
            'password' => bcrypt('123456'),
            'rol' => 'user',
            'telefono' => '777777777',
            'direccion' => '2da Comisaría Providencia',
        ]);

        \App\Models\User::create([
            'name' => 'Diego Morales Ríos',
            'email' => 'diego.morales@demo.com',
            'password' => bcrypt('123456'),
            'rol' => 'user',
            'telefono' => '888888888',
            'direccion' => 'Academia de Ciencias Policiales',
        ]);
    }
}
