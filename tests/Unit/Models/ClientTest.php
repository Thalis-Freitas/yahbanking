<?php

namespace Tests\Unit\Models;

use App\Models\Client;
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
            'last_name' => 'Doe'
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
            'uninvested_value' => 500
        ]);

        $client->refresh();
        $this->assertSame('2,500.00', $client->totalValue);
    }
}
