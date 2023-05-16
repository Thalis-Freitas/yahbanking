<?php

namespace Database\Seeders;

use App\Models\Investment;
use Illuminate\Database\Seeder;

class InvestmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Investment::factory(50)->create();
    }
}
