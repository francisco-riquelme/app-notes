<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixUserSchools extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-schools';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar y corregir las escuelas de los usuarios';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando usuarios y sus escuelas...');
        
        // Mostrar usuarios actuales
        $users = DB::table('users')->get(['name', 'codigo_funcionario', 'escuela']);
        
        foreach ($users as $user) {
            $this->line("{$user->name} - {$user->codigo_funcionario} - Escuela: {$user->escuela}");
        }
        
        $this->info('Corrigiendo escuelas...');
        
        // Corregir escuelas según código de funcionario
        $esucarCodes = ['12345', '12346', '12347', '23456', '23457', '23458'];
        $acipolCodes = ['34567', '34568', '34569'];
        
        // Actualizar usuarios de Esucar
        foreach ($esucarCodes as $code) {
            DB::table('users')->where('codigo_funcionario', $code)->update(['escuela' => 1]);
        }
        
        // Actualizar usuarios de Acipol
        foreach ($acipolCodes as $code) {
            DB::table('users')->where('codigo_funcionario', $code)->update(['escuela' => 2]);
        }
        
        $this->info('Escuelas corregidas. Verificando resultado...');
        
        // Mostrar resultado
        $users = DB::table('users')->get(['name', 'codigo_funcionario', 'escuela']);
        
        foreach ($users as $user) {
            $escuela = $user->escuela == 1 ? 'ESUCAR' : ($user->escuela == 2 ? 'ACIPOL' : 'NO ESPECIFICADA');
            $this->line("{$user->name} - {$user->codigo_funcionario} - Escuela: {$escuela}");
        }
        
        $this->info('¡Proceso completado!');
    }
}
