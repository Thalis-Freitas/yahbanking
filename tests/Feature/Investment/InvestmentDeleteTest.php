<?php

namespace Tests\Feature\Investment;

use App\Models\Client;
use App\Models\Investment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestmentDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_investment_can_be_deleted()
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->create();

        $response = $this->actingAs($user)->delete(route('investments.destroy', $investment->id));

        $this->assertCount(0, Investment::all());
        $response->assertRedirect('/');
    }

    public function test_delete_investment()
    {
        $investment = Investment::factory()->create(['name' => 'Teste']);
        $client1 = Client::factory()->create(['uninvested_value' => 800])->refresh();
        $client2 = Client::factory()->create(['uninvested_value' => 1000])->refresh();

        $investment->clients()->attach([$client1->id, $client2->id], ['invested_value' => 500]);
        $investment->deleteInvestment($investment->id);
        $this->assertDatabaseMissing('investments', ['id' => $investment->id]);

        $this->assertEquals(800, $client1->uninvested_value);
        $this->assertEquals(1000, $client2->uninvested_value);
    }
}
