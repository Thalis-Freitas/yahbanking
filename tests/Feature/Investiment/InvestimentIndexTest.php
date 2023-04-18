<?php

namespace Tests\Feature\Investiment;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Investiment;


class InvestimentIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_investiments_data_is_displayed_on_home_page()
    {
        $user = User::factory()->create();
        $investiment = Investiment::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/');

        foreach ($investiment as $investiment) {
            $response->assertSee($investiment->getAbbreviationUpper());
            $response->assertSee($investiment->name);
            $response->assertSee($investiment->description);
            $response->assertSee(route('investiments.edit', $investiment->id));
            $response->assertSee(route('investiments.show', $investiment->id));
        }
    }
}
