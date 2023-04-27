<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use App\Models\Investiment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientInvestimentTest extends TestCase
{
    use RefreshDatabase;

    public function test_investiment_requires_invested_value()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();
        $investiment = Investiment::factory()->create();

        $response = $this->actingAs($user)->post(route('clients.investiment', $client->id), [
            'invested_value' => '',
        ]);

        $response->assertSessionHasErrors(['invested_value' => 'Este campo é obrigatório.']);
    }

    public function test_investiment_invested_value_must_be_a_number()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();
        $investiment = Investiment::factory()->create();

        $response = $this->actingAs($user)->post(route('clients.investiment', $client->id), [
            'invested_value' => 'abc',
        ]);

        $response->assertSessionHasErrors(['invested_value' => 'Deve ser um número (se necessário, use "." para separar as casas decimais).']);
    }

    public function test_investiment_invested_value_must_be_greater_than_0_01()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();
        $investiment = Investiment::factory()->create();

        $response = $this->actingAs($user)->post(route('clients.investiment', $client->id), [
            'invested_value' => '-5',
        ]);

        $response->assertSessionHasErrors(['invested_value' => 'Deve ser maior que 0.01.']);
    }

    public function test_investiment_invested_value_must_be_less_than_9999999999_99()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();
        $investiment = Investiment::factory()->create();

        $response = $this->actingAs($user)->post(route('clients.investiment', $client->id), [
            'invested_value' => 99999999999_99,
        ]);

        $response->assertSessionHasErrors(['invested_value' => 'Deve ser menor que 9999999999.99.']);
    }
}
