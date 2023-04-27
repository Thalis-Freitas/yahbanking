<?php

namespace Tests\Feature\Console\Commands;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ImportDataCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_imports_data_from_api_and_saves_to_database()
    {
        $client = new Client();
        $client->name = 'George';
        $client->last_name = 'Bluth';
        $client->email = 'george.bluth@reqres.in';
        $client->save();

        Http::fake([
            'https://reqres.in/api/users?page=1' => Http::response([
                'total_pages' => 2,
                'data' => [
                    [
                        'id'=> 1,
                        'email'=> 'george.bluth@reqres.in',
                        'first_name'=> 'George',
                        'last_name'=> 'Bluth',
                        'avatar'=> 'https://reqres.in/img/faces/1-image.jpg'
                    ],
                ],
            ], 200),
            'https://reqres.in/api/users?page=2' => Http::response([
                'total_pages' => 2,
                'data' => [
                    [
                        'id'=> 2,
                        'email'=> 'janet.weaver@reqres.in',
                        'first_name'=> 'Janet',
                        'last_name'=> 'Weaver',
                        'avatar'=> 'https://reqres.in/img/faces/2-image.jpg'
                    ],
                ],
            ], 200),
        ]);

        Artisan::call('import:data');

        $this->assertDatabaseCount('clients', 2);

        $this->assertDatabaseHas('clients', [
            'name' => 'George',
            'last_name' => 'Bluth',
            'email' => 'george.bluth@reqres.in'
        ]);

        $this->assertDatabaseMissing('users', [
            'avatar' => 'public/avatars/1.jpg'
        ]);

        $this->assertDatabaseHas('clients', [
            'name' => 'Janet',
            'last_name' => 'Weaver',
            'email' => 'janet.weaver@reqres.in',
            'avatar' => 'public/avatars/2.jpg'
        ]);
    }
}
