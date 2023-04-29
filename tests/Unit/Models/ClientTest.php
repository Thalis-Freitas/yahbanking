<?php

namespace Tests\Unit\Models;

use App\Models\Client;
use App\Models\Investiment;
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

    public function test_get_investiments_not_linked()
    {
        $client = Client::factory()->create();
        $investiment1 = Investiment::factory()->create();
        $investiment2 = Investiment::factory()->create();

        $client->investiments()->attach($investiment1, ['invested_value' => 1000]);
        $notLinkedInvestiments = $client->getInvestimentsNotLinked();

        $this->assertCount(1, $notLinkedInvestiments);
        $this->assertFalse($notLinkedInvestiments->contains($investiment1));
        $this->assertTrue($notLinkedInvestiments->contains($investiment2));
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
        $investiment = Investiment::factory()->create();
        $investedValue = 700;

        $result = $client->invest($investiment, $investedValue);

        $this->assertTrue($result);
        $this->assertEquals(700, $client->invested_value);
        $this->assertEquals(700, $client->investiments()->where('id', $investiment->id)->first()->pivot->invested_value);
    }

    public function test_invest_with_insufficient_funds()
    {
        $client = Client::factory()->create([
            'uninvested_value' => 1000,
            'invested_value' => 0,
        ]);
        $investiment = Investiment::factory()->create();
        $investedValue = 1500;

        $result = $client->invest($investiment, $investedValue);

        $this->assertFalse($result);
        $this->assertEquals(0, $client->invested_value);
        $this->assertDatabaseMissing('client_investiment', [
            'client_id' => $client->id,
            'investiment_id' => $investiment->id,
        ]);
    }

    public function test_apply_value_to_investiment()
    {
        $client = Client::factory()->create([
            'uninvested_value' => 1000,
            'invested_value' => 0,
        ])->refresh();

        $investiment = Investiment::factory()->create();

        $result = $client->invest($investiment, 250);
        $result = $client->applyValueToInvestiment($investiment, 500);

        $this->assertTrue($result);
        $this->assertEquals(750, $client->invested_value);
        $this->assertEquals(250, $client->uninvested_value);

        $investimentClient = $investiment->clients()->where('client_id', $client->id)->first();
        $this->assertEquals(750, $investimentClient->pivot->invested_value);
    }

    public function test_redeem_value_from_investiment()
    {
        $client = Client::factory()->create([
            'uninvested_value' => 1000,
            'invested_value' => 0,
        ])->refresh();

        $investiment = Investiment::factory()->create();

        $result = $client->invest($investiment, 800);
        $result = $client->redeemValueFromInvestiment($investiment, 500);

        $this->assertTrue($result);
        $this->assertEquals(300, $client->invested_value);
        $this->assertEquals(700, $client->uninvested_value);

        $investimentClient = $investiment->clients()->where('client_id', $client->id)->first();
        $this->assertEquals(300, $investimentClient->pivot->invested_value);
    }
}
