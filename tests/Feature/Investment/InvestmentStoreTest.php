<?php

namespace Tests\Feature\Investment;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestmentStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_signed_in_users_can_register_a_investment()
    {
        $data = Investment::factory(1)->makeOne()->toArray();

        $this->post(route('investments.store'), $data)->assertRedirect('/login');
        $this->assertCount(0, Investment::all());
    }

    public function test_a_name_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('investments.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name' => 'Este campo é obrigatório.']);
    }

    public function test_a_abbreviation_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('investments.store'), [
            'abbreviation' => '',
        ]);

        $response->assertSessionHasErrors(['abbreviation' => 'Este campo é obrigatório.']);
    }

    public function test_a_description_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('investments.store'), [
            'description' => '',
        ]);

        $response->assertSessionHasErrors(['description' => 'Este campo é obrigatório.']);
    }

    public function test_name_must_be_unique()
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->create();

        $response = $this->actingAs($user)->post(route('investments.store'), [
            'name' => $investment->name,
        ]);

        $response->assertSessionHasErrors(['name' => 'Este nome comercial já está em uso.']);
    }
}
