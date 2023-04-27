<?php

namespace Database\Seeders;

use App\Models\Investiment;
use Illuminate\Database\Seeder;

class InvestimentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Investiment::factory(25)->create();
    }
}
