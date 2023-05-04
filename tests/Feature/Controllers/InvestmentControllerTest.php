<?php

namespace Tests\Feature\Controllers;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestmentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_method_returns_view_with_paginated_investments()
    {
        $user = User::factory()->create();
        Investment::factory()->count(15)->create();

        $response = $this->actingAs($user)->get(route('investments.index'));

        $response->assertViewIs('home');
        $response->assertViewHas('investments');
        $response->assertOk();
        $response->assertSeeText('Investimentos');
        $response->assertSeeInOrder(Investment::orderBy('created_at')
          ->take(10)->pluck('name')->toArray());
    }

    public function test_create_method_returns_view()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('investments.create'));
        $response->assertViewIs('investments.create');
        $response->assertOk();
        $response->assertSeeText('Cadastrar Investimento');
    }

    public function test_store_method_creates_a_new_investment()
    {
        $user = User::factory()->create();
        $investmentData = Investment::factory()->makeOne()->toArray();

        $response = $this->actingAs($user)->post(route('investments.store'), $investmentData);
        $investment = Investment::first();

        $response->assertRedirect(route('investments.show', $investment->id));
        $response->assertSessionHas('msg', 'Investimento cadastrado com sucesso!');
        $this->assertCount(1, Investment::all());
        $this->assertEquals($investmentData['name'], $investment->name);
        $this->assertEquals($investmentData['abbreviation'], $investment->abbreviation);
        $this->assertEquals($investmentData['description'], $investment->description);
    }

    public function test_show_method_returns_view_with_investment_data()
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->create();

        $response = $this->actingAs($user)->get(route('investments.show', $investment->id));

        $response->assertViewIs('investments.show');
        $response->assertViewHas('investment');
        $response->assertOk();
        $response->assertSeeText([
            'Informações sobre:',
            $investment->getAbbreviationUpper(),
        ]);
    }

    public function test_edit_method_returns_view_with_investment_data()
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->create();

        $response = $this->actingAs($user)->get(route('investments.edit', $investment->id));

        $response->assertViewIs('investments.edit');
        $response->assertViewHas('investment');
        $response->assertOk();
        $response->assertSeeText([
            'Atualizar Informações do Investimento:',
            $investment->getAbbreviationUpper(),
        ]);
    }

    public function test_update_method_updates_investment_data()
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->create();
        $newData = Investment::factory()->makeOne()->toArray();

        $response = $this->actingAs($user)->patch(route('investments.update', $investment->id), $newData);

        $response->assertRedirect(route('investments.show', $investment->id));
        $response->assertSessionHas('msg', 'Dados atualizados com sucesso!');
        $investment->refresh();
        $this->assertEquals($newData['abbreviation'], $investment->abbreviation);
        $this->assertEquals($newData['description'], $investment->description);
    }

    public function test_destroy_method_removes_investment_and_redirects_to_list()
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->create();

        $response = $this->actingAs($user)->delete(route('investments.destroy', $investment->id));

        $this->assertDatabaseMissing('investments', ['id' => $investment->id]);
        $response->assertRedirect('/');
        $this->assertEquals('Investimento encerrado com sucesso!', session('msg'));
    }
}
