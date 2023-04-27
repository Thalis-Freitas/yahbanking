<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ClientUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_client_can_be_updated()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)->patch(route('clients.update', $client->id), [
            'name' => $client->name,
            'last_name' => $client->last_name,
            'email' => 'novo@email.com',
            'avatar' => UploadedFile::fake()->create('avatar.png', 120),
        ]);

        $client->refresh();

        $this->assertEquals('novo@email.com', $client->email);
        $response->assertRedirect(route('clients.show', $client->id));
    }

    public function test_only_signed_in_users_can_updated_a_client()
    {
        $client = Client::factory()->create();

        $this->patch(route('clients.update', $client->id), [
            'name' => $client->name,
            'last_name' => 'Novo Sobrenome',
            'email' => $client->email,
        ])->assertRedirect('/login');

        $this->assertDatabaseMissing('clients', [
            'id' => $client->id,
            'last_name' => 'Novo Sobrenome',
        ]);
    }

    public function test_a_name_is_required()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('clients.update', $client->id), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name' => 'Este campo é obrigatório.']);
    }

    public function test_a_last_name_is_required()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('clients.update', $client->id), [
            'last_name' => '',
        ]);

        $response->assertSessionHasErrors(['last_name' => 'Este campo é obrigatório.']);
    }

    public function test_a_email_is_required()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('clients.update', $client->id), [
            'email' => '',
        ]);

        $response->assertSessionHasErrors(['email' => 'Este campo é obrigatório.']);
    }

    public function test_email_must_be_a_valid_email()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('clients.update', $client->id), [
            'email' => 'testemail.com',
        ]);

        $response->assertSessionHasErrors(['email' => 'Formato inválido.']);
    }

    public function test_email_must_be_unique()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $other_client = Client::factory()->create();

        $response = $this->actingAs($user)->patch(route('clients.update', $client->id), [
            'email' => $other_client->email,
        ]);

        $response->assertSessionHasErrors(['email' => 'Este e-mail já está em uso.']);
    }

    public function test_client_email_validation_ignores_self()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)->patch(route('clients.update', $client->id), [
            'name' => $client->name,
            'last_name' => $client->last_name,
            'email' => $client->email,
        ]);

        $response->assertSessionDoesntHaveErrors('email');
    }

    public function test_avatar_file_must_have_valid_extension()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('avatar.doc', 500);

        $response = $this->actingAs($user)->patch(route('clients.update', $client->id), [
            'avatar' => $file,
        ]);

        $response->assertSessionHasErrors([
            'avatar' => 'Deve estar no formato jpeg, jpg ou png.',
        ]);
    }
}
