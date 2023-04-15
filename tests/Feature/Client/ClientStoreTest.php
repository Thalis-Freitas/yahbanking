<?php

namespace Tests\Feature\Client;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;

class ClientStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_client_can_be_created()
    {
        $user = User::factory()->create();
        $data = Client::factory(1)->makeOne()->toArray();
        $file = UploadedFile::fake()->image('avatar.jpg', 200, 200)->size(500);

        $response = $this->actingAs($user)->post(route('clients.store'), array_merge($data, [
            'avatar' => $file,
        ]));

        $this->assertCount(1, Client::all());
        $response->assertRedirectToRoute('clients.index');
        $this->assertDatabaseHas('clients', [
            'email' => $data['email'],
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'avatar' => 'avatars/' . $file->hashName(),
        ]);
    }


    public function test_only_signed_in_users_can_register_a_client()
    {
        $data = Client::factory(1)->makeOne()->toArray();

        $this->post(route('clients.store'), $data)->assertRedirect('/login');
        $this->assertCount(0, Client::all());
    }

    public function test_a_name_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('clients.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name' => 'Este campo é obrigatório.']);
    }

    public function test_a_last_name_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('clients.store'), [
            'last_name' => '',
        ]);

        $response->assertSessionHasErrors(['last_name' => 'Este campo é obrigatório.']);
    }

    public function test_a_email_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('clients.store'), [
            'email' => '',
        ]);

        $response->assertSessionHasErrors(['email' => 'Este campo é obrigatório.']);
    }

    public function test_email_must_be_a_valid_email()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('clients.store'), [
            'email' => 'testemail.com',
        ]);

        $response->assertSessionHasErrors(['email' => 'Formato inválido.']);
    }

    public function test_email_must_be_unique()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)->post(route('clients.store'), [
            'email' => $client->email,
        ]);

        $response->assertSessionHasErrors(['email' => 'Este e-mail já está em uso.']);
    }

    public function test_avatar_file_must_have_valid_extension()
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('avatar.doc', 500);

        $response = $this->actingAs($user)->post(route('clients.store'), [
            'avatar' => $file,
        ]);

        $response->assertSessionHasErrors([
            'avatar' => 'Deve estar no formato jpeg, jpg ou png.'
        ]);
    }
    
}
