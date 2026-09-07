<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caso_notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caso_id')->constrained('casos')->cascadeOnDelete();
            $table->foreignId('autor_id')->constrained('users')->cascadeOnDelete();
            $table->text('contenido');
            $table->boolean('privada')->default(true); // false = visible para el reportante (si no es anónimo)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caso_notas');
    }
};
