<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;

class ImportData extends Command
{
    protected $signature = 'import:data';
    protected $description = 'Import data from API and save to database';

    public function handle()
    {
        $response = Http::get('https://reqres.in/api/users?page=1');
        $data = $response->json();

        for ($i = 1; $i <= $data['total_pages']; $i++) {
            $response = Http::get('https://reqres.in/api/users?page=' . $i);
            $data = $response->json();

            foreach ($data['data'] as $clientData) {
                $client = new Client();
                $client->name = $clientData['first_name'];
                $client->last_name = $clientData['last_name'];
                $client->email = $clientData['email'];

                $avatarPath = 'public/avatars/' . $clientData['id'] . '.jpg';
                $avatar = Http::get($clientData['avatar']);
                Storage::put($avatarPath, $avatar);
                $client->avatar = $avatarPath;

                $client->save();
            }
        }

        $this->info('Data imported successfully.');
    }
}
