<?php

namespace Tests\Feature\Controllers;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class InvestmentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_method_returns_page_with_investments()
    {
        $user = User::factory()->create();
        Investment::factory()->count(15)->create();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
            ->component('Home')
            ->has('investments')
        );
    }

    public function test_create_method_returns_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('investments.create'));

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
            ->component('Investments/CreateInvestment')
        );
    }

    public function test_store_method_creates_a_new_investment()
    {
        $user = User::factory()->create();
        $investmentData = Investment::factory()->makeOne()->toArray();

        $response = $this->actingAs($user)->post(route('investments.store'), $investmentData);
        $investment = Investment::first();

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('msg', 'Investimento cadastrado com sucesso!');
        $this->assertCount(1, Investment::all());
        $this->assertEquals($investmentData['name'], $investment->name);
        $this->assertEquals($investmentData['abbreviation'], $investment->abbreviation);
        $this->assertEquals($investmentData['description'], $investment->description);
    }

    public function test_show_method_returns_page_with_investment_data()
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->create();

        $response = $this->actingAs($user)->get(route('investments.show', $investment->id));

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
            ->component('Investments/ShowInvestment')
                ->has('investment')
        );
    }

    public function test_edit_method_returns_page_with_investment_data()
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->create();

        $response = $this->actingAs($user)->get(route('investments.edit', $investment->id));

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
            ->component('Investments/EditInvestment')
                ->has('investment')
        );
    }

    public function test_update_method_updates_investment_data()
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->create();
        $newData = Investment::factory()->makeOne()->toArray();

        $response = $this->actingAs($user)->patch(route('investments.update', $investment->id), $newData);
        $investment->refresh();

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('investments.show', $investment->id));
        $response->assertSessionHas('msg', 'Dados atualizados com sucesso!');
        $this->assertEquals($newData['abbreviation'], $investment->abbreviation);
        $this->assertEquals($newData['description'], $investment->description);
    }

    public function test_destroy_method_removes_investment_and_redirects_to_list()
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->create();

        $response = $this->actingAs($user)->delete(route('investments.destroy', $investment->id));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas(
            'msg',
            'Investimento '.$investment->getAbbreviationUpper().' encerrado com sucesso!'
        );
        $this->assertDatabaseMissing('investments', ['id' => $investment->id]);
        $this->assertCount(0, Investment::all());
    }
}
