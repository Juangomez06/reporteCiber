<?php

namespace Tests\Feature;

use App\Models\Caso;
use App\Models\Institucion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiCasoTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_devuelve_token(): void
    {
        $user = User::factory()->coordinador()->create(['password' => bcrypt('secreto123')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'secreto123',
            'device_name' => 'phpunit',
        ]);

        $response->assertOk()->assertJsonStructure(['token', 'user']);
    }

    public function test_login_falla_con_credenciales_invalidas(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'incorrecta',
            'device_name' => 'phpunit',
        ])->assertUnprocessable();
    }

    public function test_se_puede_reportar_un_caso_anonimo_via_api_sin_autenticacion(): void
    {
        $institucion = Institucion::factory()->create();

        $response = $this->postJson('/api/casos', [
            'institucion_id' => $institucion->id,
            'anonimo' => true,
            'tipo_acoso' => 'ciberacoso',
            'plataforma' => 'whatsapp',
            'descripcion' => str_repeat('Descripción detallada del incidente. ', 2),
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('casos', ['institucion_id' => $institucion->id, 'anonimo' => true]);
    }

    public function test_listar_casos_requiere_token(): void
    {
        $this->getJson('/api/casos')->assertUnauthorized();
    }

    public function test_coordinador_autenticado_lista_sus_casos_via_api(): void
    {
        $institucion = Institucion::factory()->create();
        $coordinador = User::factory()->coordinador()->create(['institucion_id' => $institucion->id]);
        Caso::factory()->count(2)->create(['institucion_id' => $institucion->id]);

        $response = $this->actingAs($coordinador, 'sanctum')->getJson('/api/casos');

        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
    }
}
