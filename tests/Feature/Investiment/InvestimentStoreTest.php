<?php

namespace Tests\Feature\Investiment;

use App\Models\Investiment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestimentStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_investiment_can_be_created()
    {
        $user = User::factory()->create();
        $data = Investiment::factory()->makeOne()->toArray();

        $response = $this->actingAs($user)->post(route('investiments.store'), $data);

        $this->assertCount(1, Investiment::all());
        $response->assertRedirectToRoute('investiments.show', Investiment::latest()->first());
        $this->assertDatabaseHas('investiments', [
            'name' => $data['name'],
            'abbreviation' => $data['abbreviation'],
            'description' => $data['description'],
        ]);
    }

    public function test_only_signed_in_users_can_register_a_investiment()
    {
        $data = Investiment::factory(1)->makeOne()->toArray();

        $this->post(route('investiments.store'), $data)->assertRedirect('/login');
        $this->assertCount(0, Investiment::all());
    }

    public function test_a_name_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('investiments.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name' => 'Este campo é obrigatório.']);
    }

    public function test_a_abbreviation_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('investiments.store'), [
            'abbreviation' => '',
        ]);

        $response->assertSessionHasErrors(['abbreviation' => 'Este campo é obrigatório.']);
    }

    public function test_a_description_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('investiments.store'), [
            'description' => '',
        ]);

        $response->assertSessionHasErrors(['description' => 'Este campo é obrigatório.']);
    }

    public function test_name_must_be_unique()
    {
        $user = User::factory()->create();
        $investiment = Investiment::factory()->create();

        $response = $this->actingAs($user)->post(route('investiments.store'), [
            'name' => $investiment->name,
        ]);

        $response->assertSessionHasErrors(['name' => 'Este nome comercial já está em uso.']);
    }
}
