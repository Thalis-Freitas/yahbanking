<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_client_can_be_deleted()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user)->delete(route('clients.destroy', $client->id));

        $this->assertCount(0, Client::all());
        $response->assertRedirect('clients');
    }
}
