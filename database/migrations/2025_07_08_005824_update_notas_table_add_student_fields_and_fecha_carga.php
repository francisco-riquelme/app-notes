<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas', function (Blueprint $table) {
            // Campos del estudiante
            $table->string('codigo_funcionario')->nullable()->after('user_id');
            $table->string('nombre_estudiante')->nullable()->after('codigo_funcionario');
            $table->string('grado')->nullable()->after('nombre_estudiante');
            $table->string('unidad')->nullable()->after('grado');
            $table->string('situacion')->nullable()->after('unidad');
            $table->integer('id_posicion')->nullable()->after('situacion');
            $table->string('grupo')->nullable()->after('id_posicion');
            $table->string('sede')->nullable()->after('grupo');
            $table->string('sexo')->nullable()->after('sede');
            
            // Fecha de carga para filtros
            $table->date('fecha_carga')->nullable()->after('sexo');
            
            // Campo para identificar qué admin cargó los datos
            $table->unsignedBigInteger('admin_id')->nullable()->after('fecha_carga');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notas', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn([
                'codigo_funcionario',
                'nombre_estudiante', 
                'grado',
                'unidad',
                'situacion',
                'id_posicion',
                'grupo',
                'sede',
                'sexo',
                'fecha_carga',
                'admin_id'
            ]);
        });
    }
};
