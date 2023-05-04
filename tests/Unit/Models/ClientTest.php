<?php

namespace Tests\Unit\Models;

use App\Models\Client;
use App\Models\Investment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_the_correct_fillable_properties()
    {
        $fillable = ['name', 'last_name', 'email', 'avatar', 'uninvested_value'];
        $client = new Client();

        $this->assertEquals($fillable, $client->getFillable());
    }

    public function test_it_returns_the_full_name()
    {
        $client = Client::factory()->create([
            'name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('John Doe', $client->getFullName());
    }

    public function test_it_returns_the_correct_avatar_url()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $path = $file->store('avatars', 'public');

        $client = Client::factory()->create(['avatar' => $path]);

        $this->assertEquals(Storage::url($path), $client->getAvatarUrl());
    }

    public function test_default_values()
    {
        $client = Client::factory()->create();

        $this->assertSame(0, $client->uninvested_value);
        $this->assertSame(0, $client->invested_value);
    }

    public function test_total_value_is_sum_invested_value_and_uninvested_value()
    {
        $client = Client::factory()->create([
            'invested_value' => 2000,
            'uninvested_value' => 500,
        ]);

        $client->refresh();
        $this->assertSame('2500.00', $client->totalValue);
    }

    public function test_get_investments_not_linked()
    {
        $client = Client::factory()->create();
        $investment1 = Investment::factory()->create();
        $investment2 = Investment::factory()->create();

        $client->investments()->attach($investment1, ['invested_value' => 1000]);
        $notLinkedInvestments = $client->getInvestmentsNotLinked();

        $this->assertCount(1, $notLinkedInvestments);
        $this->assertFalse($notLinkedInvestments->contains($investment1));
        $this->assertTrue($notLinkedInvestments->contains($investment2));
    }

    public function test_deposit()
    {
        $client = Client::factory()->create();
        $uninvestedValue = 100;

        $result = $client->deposit($uninvestedValue);

        $this->assertTrue($result);
        $this->assertEquals($client->uninvested_value, $uninvestedValue);
    }

    public function test_successfully_invest()
    {
        $client = Client::factory()->create([
            'uninvested_value' => 1000,
            'invested_value' => 0,
        ])->refresh();
        $investment = Investment::factory()->create();
        $investedValue = 700;

        $result = $client->invest($investment, $investedValue);

        $this->assertTrue($result);
        $this->assertEquals(700, $client->invested_value);
        $this->assertEquals(700, $client->investments()->where('id', $investment->id)->first()->pivot->invested_value);
    }

    public function test_invest_with_insufficient_funds()
    {
        $client = Client::factory()->create([
            'uninvested_value' => 1000,
            'invested_value' => 0,
        ]);
        $investment = Investment::factory()->create();
        $investedValue = 1500;

        $result = $client->invest($investment, $investedValue);

        $this->assertFalse($result);
        $this->assertEquals(0, $client->invested_value);
        $this->assertDatabaseMissing('client_investment', [
            'client_id' => $client->id,
            'investment_id' => $investment->id,
        ]);
    }

    public function test_apply_value_to_investment()
    {
        $client = Client::factory()->create([
            'uninvested_value' => 1000,
            'invested_value' => 0,
        ])->refresh();

        $investment = Investment::factory()->create();

        $result = $client->invest($investment, 250);
        $result = $client->applyValueToInvestment($investment, 500);

        $this->assertTrue($result);
        $this->assertEquals(750, $client->invested_value);
        $this->assertEquals(250, $client->uninvested_value);

        $investmentClient = $investment->clients()->where('client_id', $client->id)->first();
        $this->assertEquals(750, $investmentClient->pivot->invested_value);
    }

    public function test_redeem_value_from_investment()
    {
        $client = Client::factory()->create([
            'uninvested_value' => 1000,
            'invested_value' => 0,
        ])->refresh();

        $investment = Investment::factory()->create();

        $result = $client->invest($investment, 800);
        $result = $client->redeemValueFromInvestment($investment, 500);

        $this->assertTrue($result);
        $this->assertEquals(300, $client->invested_value);
        $this->assertEquals(700, $client->uninvested_value);

        $investmentClient = $investment->clients()->where('client_id', $client->id)->first();
        $this->assertEquals(300, $investmentClient->pivot->invested_value);
    }
}
