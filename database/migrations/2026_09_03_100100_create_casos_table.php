<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('casos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('institucion_id')->constrained('instituciones')->cascadeOnDelete();

            // Nulo si el reporte es anónimo (nunca se guarda el autor)
            $table->foreignId('reporter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('anonimo')->default(false);

            $table->foreignId('orientador_id')->nullable()->constrained('users')->nullOnDelete();

            $table->enum('tipo_acoso', [
                'ciberacoso', 'suplantacion', 'sextorsion', 'grooming',
                'discurso_odio', 'exclusion_social', 'otro',
            ]);
            $table->enum('plataforma', [
                'whatsapp', 'instagram', 'tiktok', 'facebook', 'x',
                'juego_online', 'correo', 'presencial_digital', 'otro',
            ]);

            $table->text('descripcion');
            $table->enum('estado', ['nuevo', 'en_revision', 'asignado', 'en_proceso', 'resuelto', 'cerrado'])
                ->default('nuevo');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica'])->default('media');

            $table->timestamp('resuelto_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('casos');
    }
};
