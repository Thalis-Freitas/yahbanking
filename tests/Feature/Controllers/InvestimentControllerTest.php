<?php

namespace Tests\Feature\Controllers;

use App\Models\Investiment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestimentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_method_returns_view_with_paginated_investiments()
    {
        $user = User::factory()->create();
        Investiment::factory()->count(15)->create();

        $response = $this->actingAs($user)->get(route('investiments.index'));

        $response->assertViewIs('home');
        $response->assertViewHas('investiments');
        $response->assertOk();
        $response->assertSeeText('Investimentos');
        $response->assertSeeInOrder(Investiment::orderBy('created_at')
          ->take(10)->pluck('name')->toArray());
    }

    public function test_create_method_returns_view()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('investiments.create'));
        $response->assertViewIs('investiments.create');
        $response->assertOk();
        $response->assertSeeText('Cadastrar Investimento');
    }

    public function test_store_method_creates_a_new_investiment()
    {
        $user = User::factory()->create();
        $investimentData = Investiment::factory()->makeOne()->toArray();

        $response = $this->actingAs($user)->post(route('investiments.store'), $investimentData);
        $investiment = Investiment::first();

        $response->assertRedirect(route('investiments.show', $investiment->id));
        $response->assertSessionHas('msg', 'Investimento cadastrado com sucesso!');
        $this->assertCount(1, Investiment::all());
        $this->assertEquals($investimentData['name'], $investiment->name);
        $this->assertEquals($investimentData['abbreviation'], $investiment->abbreviation);
        $this->assertEquals($investimentData['description'], $investiment->description);
    }

    public function test_show_method_returns_view_with_investiment_data()
    {
        $user = User::factory()->create();
        $investiment = Investiment::factory()->create();

        $response = $this->actingAs($user)->get(route('investiments.show', $investiment->id));

        $response->assertViewIs('investiments.show');
        $response->assertViewHas('investiment');
        $response->assertOk();
        $response->assertSeeText([
            'Informações sobre:',
            $investiment->getAbbreviationUpper(),
        ]);
    }

    public function test_edit_method_returns_view_with_investiment_data()
    {
        $user = User::factory()->create();
        $investiment = Investiment::factory()->create();

        $response = $this->actingAs($user)->get(route('investiments.edit', $investiment->id));

        $response->assertViewIs('investiments.edit');
        $response->assertViewHas('investiment');
        $response->assertOk();
        $response->assertSeeText([
            'Atualizar Informações do Investimento:',
            $investiment->getAbbreviationUpper(),
        ]);
    }

    public function test_update_method_updates_investiment_data()
    {
        $user = User::factory()->create();
        $investiment = Investiment::factory()->create();
        $newData = Investiment::factory()->makeOne()->toArray();

        $response = $this->actingAs($user)->patch(route('investiments.update', $investiment->id), $newData);

        $response->assertRedirect(route('investiments.show', $investiment->id));
        $response->assertSessionHas('msg', 'Dados atualizados com sucesso!');
        $investiment->refresh();
        $this->assertEquals($newData['abbreviation'], $investiment->abbreviation);
        $this->assertEquals($newData['description'], $investiment->description);
    }

    public function test_destroy_method_removes_investiment_and_redirects_to_list()
    {
        $user = User::factory()->create();
        $investiment = Investiment::factory()->create();

        $response = $this->actingAs($user)->delete(route('investiments.destroy', $investiment->id));

        $this->assertDatabaseMissing('investiments', ['id' => $investiment->id]);
        $response->assertRedirect('/');
        $this->assertEquals('Investimento encerrado com sucesso!', session('msg'));
    }
}
