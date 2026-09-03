<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('sede')->nullable();
            $table->string('jornada')->nullable();
            $table->date('fechaini')->nullable();
            $table->string('estrato')->nullable();
            $table->string('sisben')->nullable();
            $table->string('doc')->unique(); // Número de documento
            $table->string('tipodoc')->nullable();
            $table->string('apellido1')->nullable();
            $table->string('apellido2')->nullable();
            $table->string('nombre1')->nullable();
            $table->string('nombre2')->nullable();
            $table->string('genero')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('barrio')->nullable();
            $table->string('eps')->nullable();
            $table->string('tipo_sangre')->nullable();
            $table->string('discapacidad')->nullable();
            $table->string('pais_origen')->nullable();
            $table->string('telefono')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
