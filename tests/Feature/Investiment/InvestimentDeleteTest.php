<?php

namespace Tests\Feature\Investiment;

use App\Models\Investiment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestimentDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_investiment_can_be_deleted()
    {
        $user = User::factory()->create();
        $investiment = Investiment::factory()->create();

        $response = $this->actingAs($user)->delete(route('investiments.destroy', $investiment->id));

        $this->assertCount(0, Investiment::all());
        $response->assertRedirect('/');
    }
}
