<?php

namespace Tests\Feature;

use App\Models\Caso;
use App\Models\Institucion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class GestionCasoTest extends TestCase
{
    use RefreshDatabase;

    public function test_coordinador_puede_ver_casos_de_su_institucion(): void
    {
        $institucion = Institucion::factory()->create();
        $coordinador = User::factory()->coordinador()->create(['institucion_id' => $institucion->id]);
        $caso = Caso::factory()->create(['institucion_id' => $institucion->id]);

        $this->actingAs($coordinador)
            ->get(route('casos.show', $caso))
            ->assertOk();
    }

    public function test_estudiante_no_puede_ver_un_caso_que_no_le_pertenece(): void
    {
        $institucion = Institucion::factory()->create();
        $estudiante = User::factory()->estudiante()->create(['institucion_id' => $institucion->id]);
        $caso = Caso::factory()->create(['institucion_id' => $institucion->id]);

        $this->actingAs($estudiante)
            ->get(route('casos.show', $caso))
            ->assertForbidden();
    }

    public function test_coordinador_puede_asignar_un_orientador_y_este_es_notificado(): void
    {
        Notification::fake();

        $institucion = Institucion::factory()->create();
        $coordinador = User::factory()->coordinador()->create(['institucion_id' => $institucion->id]);
        $orientador = User::factory()->estudiante()->create(['institucion_id' => $institucion->id]);
        $caso = Caso::factory()->create(['institucion_id' => $institucion->id, 'estado' => 'nuevo']);

        $this->actingAs($coordinador)
            ->post(route('casos.asignar', $caso), ['orientador_id' => $orientador->id])
            ->assertRedirect();

        $caso->refresh();
        $this->assertEquals($orientador->id, $caso->orientador_id);
        $this->assertEquals('asignado', $caso->estado);
        Notification::assertSentTo($orientador, \App\Notifications\CasoAsignadoNotification::class);
    }

    public function test_estudiante_no_puede_asignar_casos(): void
    {
        $institucion = Institucion::factory()->create();
        $estudiante = User::factory()->estudiante()->create(['institucion_id' => $institucion->id]);
        $otro = User::factory()->estudiante()->create(['institucion_id' => $institucion->id]);
        $caso = Caso::factory()->create(['institucion_id' => $institucion->id, 'orientador_id' => $estudiante->id]);

        $this->actingAs($estudiante)
            ->post(route('casos.asignar', $caso), ['orientador_id' => $otro->id])
            ->assertForbidden();
    }

    public function test_orientador_asignado_puede_cambiar_el_estado_y_queda_en_el_historial(): void
    {
        $institucion = Institucion::factory()->create();
        $orientador = User::factory()->estudiante()->create(['institucion_id' => $institucion->id]);
        $caso = Caso::factory()->create(['institucion_id' => $institucion->id, 'orientador_id' => $orientador->id, 'estado' => 'asignado']);

        $this->actingAs($orientador)
            ->patch(route('casos.estado', $caso), ['estado' => 'en_proceso'])
            ->assertRedirect();

        $caso->refresh();
        $this->assertEquals('en_proceso', $caso->estado);
        $this->assertDatabaseHas('caso_historiales', [
            'caso_id' => $caso->id,
            'accion' => 'cambio_estado',
            'valor_nuevo' => 'en_proceso',
        ]);
    }

    public function test_solo_coordinador_gestiona_instituciones(): void
    {
        $estudiante = User::factory()->estudiante()->create();

        $this->actingAs($estudiante)
            ->get(route('instituciones.index'))
            ->assertForbidden();
    }

    public function test_coordinador_puede_crear_institucion(): void
    {
        $coordinador = User::factory()->coordinador()->create();

        $this->actingAs($coordinador)->post(route('instituciones.store'), [
            'nombre' => 'Colegio de Prueba',
            'codigo' => 'COL-001',
            'ciudad' => 'Bogotá',
        ])->assertRedirect(route('instituciones.index'));

        $this->assertDatabaseHas('instituciones', ['codigo' => 'COL-001']);
    }
}
