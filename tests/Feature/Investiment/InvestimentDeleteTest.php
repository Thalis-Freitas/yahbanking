<?php

namespace Tests\Feature\Investiment;

use App\Models\Client;
use App\Models\Investiment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestimentDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_investiment_can_be_deleted()
    {
        $user = User::factory()->create();
        $investiment = Investiment::factory()->create();

        $response = $this->actingAs($user)->delete(route('investiments.destroy', $investiment->id));

        $this->assertCount(0, Investiment::all());
        $response->assertRedirect('/');
    }

    public function test_delete_investiment()
    {
        $investiment = Investiment::factory()->create(['name' => 'Teste']);
        $client1 = Client::factory()->create(['uninvested_value' => 800])->refresh();
        $client2 = Client::factory()->create(['uninvested_value' => 1000])->refresh();

        $investiment->clients()->attach([$client1->id, $client2->id], ['invested_value' => 500]);
        $investiment->deleteInvestiment($investiment->id);
        $this->assertDatabaseMissing('investiments', ['id' => $investiment->id]);

        $this->assertEquals(800, $client1->uninvested_value);
        $this->assertEquals(1000, $client2->uninvested_value);
    }
}
