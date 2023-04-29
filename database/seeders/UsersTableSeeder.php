<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Gestão Yahbanking',
            'email' => 'mariner4_gestao@yahbanking.com',
            'password' => bcrypt('password'),
        ]);
    }
}
