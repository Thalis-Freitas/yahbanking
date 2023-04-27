<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_clients_data_is_displayed_on_clients_index_page()
    {
        $user = User::factory()->create();
        $clients = Client::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('clients.index'));

        foreach ($clients as $client) {
            $response->assertSee($client->getFullName());
            $response->assertSee($client->email);
            $response->assertSee(route('clients.edit', $client->id));
            $response->assertSee(route('clients.show', $client->id));
        }
    }
}
