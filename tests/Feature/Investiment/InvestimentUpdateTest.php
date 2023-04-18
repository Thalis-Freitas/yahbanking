<?php

namespace Tests\Feature\Investiment;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Investiment;

class InvestimentUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_investiment_can_be_updated()
    {
        $user = User::factory()->create();
        $investiment = Investiment::factory()->create();

        $response = $this->actingAs($user)->patch(route('investiments.update', $investiment->id), [
            'name' => $investiment->name,
            'abbreviation' => 'CDB',
            'description' => $investiment->description,
        ]);

        $investiment->refresh();

        $this->assertEquals('CDB', $investiment->abbreviation);
        $response->assertRedirect(route('investiments.show', $investiment->id));
    }

    public function test_only_signed_in_users_can_updated_a_investiment()
    {
        $investiment = Investiment::factory()->create();

        $this->patch(route('investiments.update', $investiment->id), [
            'name' => $investiment->name,
            'abbreviation' => 'CDB',
            'description' => $investiment->description,
        ])->assertRedirect('/login');

        $this->assertDatabaseMissing('investiments', [
            'id' => $investiment->id,
            'abbreviation' => $investiment->id,
        ]);
    }

    public function test_a_name_is_required()
    {
        $investiment = Investiment::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('investiments.update', $investiment->id), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name' => 'Este campo é obrigatório.']);
    }

    public function test_a_abbreviation_is_required()
    {
        $investiment = Investiment::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('investiments.update', $investiment->id), [
            'abbreviation' => '',
        ]);

        $response->assertSessionHasErrors(['abbreviation' => 'Este campo é obrigatório.']);
    }

    public function test_a_description_is_required()
    {
        $investiment = Investiment::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('investiments.update', $investiment->id), [
            'description' => '',
        ]);

        $response->assertSessionHasErrors(['description' => 'Este campo é obrigatório.']);
    }

    public function test_name_must_be_unique()
    {
        $user = User::factory()->create();
        $investiment = Investiment::factory()->create();
        $other_investiment = Investiment::factory()->create();

        $response = $this->actingAs($user)->patch(route('investiments.update', $investiment->id), [
            'name' => $other_investiment->name,
        ]);

        $response->assertSessionHasErrors(['name' => 'Este nome comercial já está em uso.']);
    }

    public function test_investiment_name_validation_ignores_self()
    {
        $user = User::factory()->create();
        $investiment = Investiment::factory()->create();

        $response = $this->actingAs($user)->patch(route('investiments.update', $investiment->id), [
            'name' => $investiment->name,
            'abbreviation' => $investiment->abbreviation,
            'description' => $investiment->description,
        ]);

        $response->assertSessionDoesntHaveErrors('name');
    }
}
