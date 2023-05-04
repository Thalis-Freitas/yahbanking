<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientInvestmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_investment_requires_invested_value()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('clients.investment', $client->id), [
            'invested_value' => '',
        ]);

        $response->assertSessionHasErrors(['invested_value' => 'Este campo é obrigatório.']);
    }

    public function test_investment_invested_value_must_be_a_number()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('clients.investment', $client->id), [
            'invested_value' => 'abc',
        ]);

        $response->assertSessionHasErrors(['invested_value' => 'Deve ser um número (se necessário, use "." para separar as casas decimais).']);
    }

    public function test_investment_invested_value_must_be_greater_than_0_01()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('clients.investment', $client->id), [
            'invested_value' => '-5',
        ]);

        $response->assertSessionHasErrors(['invested_value' => 'Deve ser maior que 0.01.']);
    }

    public function test_investment_invested_value_must_be_less_than_9999999999_99()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('clients.investment', $client->id), [
            'invested_value' => 99999999999_99,
        ]);

        $response->assertSessionHasErrors(['invested_value' => 'Deve ser menor que 9999999999.99.']);
    }
}
