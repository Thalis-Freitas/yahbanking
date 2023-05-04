<?php

namespace Tests\Feature\Investment;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestmentIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_investments_data_is_displayed_on_home_page()
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/');

        foreach ($investment as $investment) {
            $response->assertSee($investment->getAbbreviationUpper());
            $response->assertSee($investment->name);
            $response->assertSee($investment->description);
            $response->assertSee(route('investments.edit', $investment->id));
            $response->assertSee(route('investments.show', $investment->id));
        }
    }
}
