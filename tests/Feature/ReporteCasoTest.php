<?php

namespace Tests\Feature;

use App\Models\Caso;
use App\Models\Institucion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ReporteCasoTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_visitante_puede_reportar_un_caso_anonimo(): void
    {
        Notification::fake();
        $institucion = Institucion::factory()->create();

        $response = $this->post(route('casos.store'), [
            'institucion_id' => $institucion->id,
            'anonimo' => '1',
            'tipo_acoso' => 'ciberacoso',
            'plataforma' => 'whatsapp',
            'descripcion' => str_repeat('Descripción detallada del incidente. ', 2),
        ]);

        $caso = Caso::first();

        $response->assertRedirect(route('casos.confirmacion', $caso->codigo));
        $this->assertTrue($caso->anonimo);
        $this->assertNull($caso->reporter_id);
    }

    public function test_un_usuario_autenticado_puede_reportar_sin_anonimato_y_queda_registrado(): void
    {
        $institucion = Institucion::factory()->create();
        $usuario = User::factory()->estudiante()->create(['institucion_id' => $institucion->id]);

        $this->actingAs($usuario)->post(route('casos.store'), [
            'institucion_id' => $institucion->id,
            'tipo_acoso' => 'grooming',
            'plataforma' => 'instagram',
            'descripcion' => str_repeat('Descripción detallada del incidente. ', 2),
        ]);

        $caso = Caso::first();
        $this->assertFalse($caso->anonimo);
        $this->assertEquals($usuario->id, $caso->reporter_id);
    }

    public function test_el_formulario_valida_descripcion_minima_y_tipo_de_acoso(): void
    {
        $institucion = Institucion::factory()->create();

        $response = $this->post(route('casos.store'), [
            'institucion_id' => $institucion->id,
            'tipo_acoso' => 'no_existe',
            'plataforma' => 'whatsapp',
            'descripcion' => 'muy corto',
        ]);

        $response->assertSessionHasErrors(['tipo_acoso', 'descripcion']);
    }

    public function test_puede_adjuntar_evidencia_al_reportar(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');
        $institucion = Institucion::factory()->create();

        $this->post(route('casos.store'), [
            'institucion_id' => $institucion->id,
            'anonimo' => '1',
            'tipo_acoso' => 'sextorsion',
            'plataforma' => 'tiktok',
            'descripcion' => str_repeat('Descripción detallada del incidente. ', 2),
            'evidencias' => [UploadedFile::fake()->image('captura.jpg')],
        ]);

        $caso = Caso::first();
        $this->assertCount(1, $caso->evidencias);
    }
}
