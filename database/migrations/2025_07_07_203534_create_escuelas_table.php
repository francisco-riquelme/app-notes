<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('escuelas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_escuela');
            $table->timestamps();
        });

        // Insertar las escuelas actuales
        DB::table('escuelas')->insert([
            ['nombre_escuela' => 'Escuela de Suboficiales de Carabineros de Chile'],
            ['nombre_escuela' => 'Academia de Ciencias Policiales de Carabineros de Chile'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('escuelas');
    }
};
