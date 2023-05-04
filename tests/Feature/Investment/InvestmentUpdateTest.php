<?php

namespace Tests\Feature\Investment;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestmentUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_investment_can_be_updated()
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->create();

        $response = $this->actingAs($user)->patch(route('investments.update', $investment->id), [
            'name' => $investment->name,
            'abbreviation' => 'CDB',
            'description' => $investment->description,
        ]);

        $investment->refresh();

        $this->assertEquals('CDB', $investment->abbreviation);
        $response->assertRedirect(route('investments.show', $investment->id));
    }

    public function test_only_signed_in_users_can_updated_a_investment()
    {
        $investment = Investment::factory()->create();

        $this->patch(route('investments.update', $investment->id), [
            'name' => $investment->name,
            'abbreviation' => 'CDB',
            'description' => $investment->description,
        ])->assertRedirect('/login');

        $this->assertDatabaseMissing('investments', [
            'id' => $investment->id,
            'abbreviation' => $investment->id,
        ]);
    }

    public function test_a_name_is_required()
    {
        $investment = Investment::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('investments.update', $investment->id), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name' => 'Este campo é obrigatório.']);
    }

    public function test_a_abbreviation_is_required()
    {
        $investment = Investment::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('investments.update', $investment->id), [
            'abbreviation' => '',
        ]);

        $response->assertSessionHasErrors(['abbreviation' => 'Este campo é obrigatório.']);
    }

    public function test_a_description_is_required()
    {
        $investment = Investment::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('investments.update', $investment->id), [
            'description' => '',
        ]);

        $response->assertSessionHasErrors(['description' => 'Este campo é obrigatório.']);
    }

    public function test_name_must_be_unique()
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->create();
        $other_investment = Investment::factory()->create();

        $response = $this->actingAs($user)->patch(route('investments.update', $investment->id), [
            'name' => $other_investment->name,
        ]);

        $response->assertSessionHasErrors(['name' => 'Este nome comercial já está em uso.']);
    }

    public function test_investment_name_validation_ignores_self()
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->create();

        $response = $this->actingAs($user)->patch(route('investments.update', $investment->id), [
            'name' => $investment->name,
            'abbreviation' => $investment->abbreviation,
            'description' => $investment->description,
        ]);

        $response->assertSessionDoesntHaveErrors('name');
    }
}
