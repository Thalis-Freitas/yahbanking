<?php

namespace Tests\Feature\Controllers;

use App\Models\Client;
use App\Models\Investment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_method_returns_page_with_paginated_clients()
    {
        $user = User::factory()->create();
        Client::factory()->count(15)->create();

        $response = $this->actingAs($user)->get(route('clients.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
            ->component('Clients/ClientsIndex')
            ->has('clients')
        );
    }

    public function test_create_method_returns_view()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('clients.create'));
        $response->assertViewIs('clients.create');
        $response->assertOk();
        $response->assertSeeText('Cadastrar Cliente');
    }

    public function test_store_method_creates_a_new_client()
    {
        $user = User::factory()->create();
        Storage::fake('public');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $clientData = Client::factory()->makeOne()->toArray();

        $response = $this->actingAs($user)->post(route('clients.store'), array_merge($clientData, [
            'avatar' => $file,
        ]));
        $client = Client::first();

        $response->assertRedirect(route('clients.index'));
        $response->assertSessionHas('msg', 'Cliente cadastrado com sucesso!');
        $this->assertCount(1, Client::all());
        $this->assertEquals($clientData['name'], $client->name);
        $this->assertEquals($clientData['email'], $client->email);
        $this->assertNotNull($client->avatar);
        Storage::disk('public')->assertExists($client->avatar);
    }

    public function test_show_method_returns_view_with_client_data()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)->get(route('clients.show', $client->id));

        $response->assertViewIs('clients.show');
        $response->assertViewHas('client');
        $response->assertOk();
        $response->assertSeeText([
            'Informações sobre:',
            $client->getFullName(),
        ]);
    }

    public function test_edit_method_returns_view_with_client_data()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)->get(route('clients.edit', $client->id));

        $response->assertViewIs('clients.edit');
        $response->assertViewHas('client');
        $response->assertOk();
        $response->assertSeeText(['Atualizar Informações de', $client->getFullName()]);
    }

    public function test_update_method_updates_client_data()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $newData = Client::factory()->makeOne()->toArray();

        $response = $this->actingAs($user)->patch(route('clients.update', $client->id), $newData);
        $client->refresh();

        $response->assertRedirect(route('clients.show', $client->id));
        $response->assertSessionHas('msg', 'Dados atualizados com sucesso!');
        $this->assertEquals($newData['name'], $client->name);
        $this->assertEquals($newData['email'], $client->email);
    }

    public function test_destroy_method_removes_client_and_redirects_to_list()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)->delete(route('clients.destroy', $client->id));

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
        $response->assertRedirect('/clients');
        $this->assertEquals('Cliente removido com sucesso!', session('msg'));
    }

    public function test_destroy_method_removes_client_and_investments()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $investment = Investment::factory()->create();
        $client->investments()->attach($investment, ['invested_value' => 1000]);

        $response = $this->actingAs($user)->delete(route('clients.destroy', $client->id));

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
        $this->assertDatabaseMissing('client_investment', ['client_id' => $client->id, 'investment_id' => $investment->id]);
        $response->assertRedirect('/clients');
        $this->assertEquals('Cliente removido com sucesso!', session('msg'));
    }

    public function test_deposit_successfully()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)->patch(route('clients.deposit', $client->id), [
            'uninvested_value' => 50.30,
        ]);
        $client->refresh();

        $response->assertStatus(302)->assertSessionHas('msg', 'Valor depositado com sucesso!');
        $this->assertEquals(50.30, $client->uninvested_value);
    }

    public function test_deposit_should_add_deposited_amount_to_uninvested_value()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create([
            'uninvested_value' => 100.00,
        ]);

        $this->actingAs($user)->patch(route('clients.deposit', $client->id), [
            'uninvested_value' => 50.00,
        ]);
        $client->refresh();

        $this->assertEquals(150.00, $client->uninvested_value);
    }

    public function test_investment_successfully()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create([
            'uninvested_value' => 1000.00,
            'invested_value' => 0.00,
        ]);

        $investment = Investment::factory()->create();

        $data = [
            'investment' => json_encode(['id' => $investment->id]),
            'invested_value' => 200.00,
        ];

        $response = $this->actingAs($user)->post(route('clients.investment', $client->id), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('clients.show', $client->id));
        $response->assertSessionHas(
            'msg',
            'Cliente vinculado com sucesso ao investimento '.$investment->getAbbreviationAndName()
        );

        $client = $client->fresh();
        $investment = $investment->fresh();

        $this->assertEquals(200.00, $client->invested_value);
        $this->assertEquals(800.00, $client->uninvested_value);
        $this->assertEquals(200.00, $client->investments()->first()->pivot->invested_value);
        $this->assertEquals(200.00, $investment->clients()->first()->invested_value);
    }

    public function test_investment_fails_if_insufficient_funds()
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->create();
        $client = Client::factory()->create([
            'uninvested_value' => 100.00,
            'invested_value' => 0.00,
        ]);

        $data = [
            'investment' => json_encode(['id' => $investment->id]),
            'invested_value' => 200.00,
        ];

        $response = $this->actingAs($user)->post(route('clients.investment', $client->id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'invested_value' => 'Não é possível aplicar um valor maior do que o disponível (valor não investido).',
        ]);

        $client = $client->fresh();
        $investment = $investment->fresh();

        $this->assertEquals(0.00, $client->invested_value);
        $this->assertEquals(100.00, $client->uninvested_value);
        $this->assertEmpty($client->investments);
        $this->assertEmpty($investment->clients);
    }
}
