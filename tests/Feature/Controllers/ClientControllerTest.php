<?php

namespace Tests\Feature\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_method_returns_view_with_paginated_clients()
    {
        $user = User::factory()->create();
        Client::factory()->count(15)->create();

        $response = $this->actingAs($user)->get(route('clients.index'));

        $response->assertViewIs('clients.index');
        $response->assertViewHas('clients');
        $response->assertOk();
        $response->assertSeeText('Gerenciamento de Clientes');
        $response->assertSeeInOrder(Client::orderByDesc('created_at')
          ->take(10)->pluck('name')->toArray());
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
          'avatar' => $file
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
          $client->getFullName()
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
        $response->assertSeeText(['Atualizar Informações do Cliente', $client->getFullName()]);
    }

    public function test_update_method_updates_client_data()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $newData = Client::factory()->makeOne()->toArray();

        $response = $this->actingAs($user)->patch(route('clients.update', $client->id), $newData);

        $response->assertRedirect(route('clients.show', $client->id));
        $response->assertSessionHas('msg', 'Dados atualizados com sucesso!');
        $client->refresh();
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

}
