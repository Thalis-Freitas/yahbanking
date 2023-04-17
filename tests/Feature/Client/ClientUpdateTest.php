<?php

namespace Tests\Feature\Client;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;

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
            'avatar' => UploadedFile::fake()->create('avatar.png', 120)
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
            'email' => $client->email
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
            'avatar' => 'Deve estar no formato jpeg, jpg ou png.'
        ]);
    }

    public function test_deposit_requires_uninvested_value()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('clients.deposit', $client->id), [
            'uninvested_value' => ''
        ]);

        $response->assertSessionHasErrors(['uninvested_value' => 'Este campo é obrigatório.']);
    }

    public function test_deposit_uninvested_value_must_be_a_number()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('clients.deposit', $client->id), [
            'uninvested_value' => 'abc'
        ]);

        $response->assertSessionHasErrors(['uninvested_value' =>
            'Deve ser um número (se necessário, use "." para separar as casas decimais).']);
    }

    public function test_deposit_uninvested_value_must_be_greater_than_0_01()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('clients.deposit', $client->id), [
            'uninvested_value' => '-5'
        ]);

        $response->assertSessionHasErrors(['uninvested_value' => 'Deve ser maior que 0.01.']);
    }

    public function test_deposit_uninvested_value_must_be_less_than_9999999999_99()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('clients.deposit', $client->id), [
            'uninvested_value' => 99999999999_99
        ]);

        $response->assertSessionHasErrors(['uninvested_value' => 'Deve ser menor que 9999999999.99.']);
    }
}
