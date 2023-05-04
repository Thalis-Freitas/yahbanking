<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyValuesRequest;
use App\Http\Requests\ClientInvestmentStoreRequest;
use App\Http\Requests\ClientStoreRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\RedeemValuesRequest;
use App\Models\Client;
use App\Models\Investment;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(10);

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(ClientStoreRequest $request)
    {
        $input = $request->except('uninvested_value');
        if (array_key_exists('avatar', $input)) {
            $input['avatar'] = $input['avatar']->store('avatars', 'public');
        }

        Client::create($input);

        return redirect()->route('clients.index')
            ->with('msg', 'Cliente cadastrado com sucesso!');
    }

    public function show($id)
    {
        $client = Client::find($id);
        $investments = $client->getInvestmentsNotLinked();

        return view('clients.show', compact('client', 'investments'));
    }

    public function edit($id)
    {
        $client = Client::find($id);

        return view('clients.edit', compact('client'));
    }

    public function update(ClientUpdateRequest $request, $id)
    {
        $input = $request->all();
        if (array_key_exists('avatar', $input)) {
            $input['avatar'] = $input['avatar']->store('avatars', 'public');
        }
        $client = Client::find($id);

        $client->update($input);

        return redirect()->route('clients.show', $id)
            ->with('msg', 'Dados atualizados com sucesso!');
    }

    public function destroy($id)
    {
        Client::find($id)->investments()->detach();

        Client::destroy($id);

        return redirect('clients')->with('msg', 'Cliente removido com sucesso!');
    }

    public function deposit(DepositRequest $request, $id)
    {
        $client = Client::find($id);
        $uninvestedValue = $request->uninvested_value;
        $client->deposit($uninvestedValue);

        return redirect()->route('clients.show', $id)
            ->with('msg', 'Valor depositado com sucesso!');
    }

    public function investment(ClientInvestmentStoreRequest $request, $id)
    {
        $data = json_decode($request->investment);
        $investment = Investment::find($data->id);
        $client = Client::find($id);
        $investedValue = $request->invested_value;

        if (! $client->invest($investment, $investedValue)) {
            return redirect()->back()->withErrors([
                'invested_value' => 'Não é possível aplicar um valor maior do que o disponível (valor não investido).',
            ]);
        }

        return redirect()->route('clients.show', $id)
            ->with('msg', 'Cliente vinculado com sucesso ao investimento '.$investment->getAbbreviationAndName());
    }

    public function apply(ApplyValuesRequest $request, $id)
    {
        $client = Client::find($id);
        $investment = Investment::find(decrypt($request->input('investment_id')));
        $valueToApply = $request->value_to_apply;

        if (! $client->applyValueToInvestment($investment, $valueToApply)) {
            return redirect()->back()->withErrors([
                'value_to_apply' => 'Não é possível aplicar um valor maior do que o disponível (valor não investido).',
            ]);
        }

        return redirect()->route('clients.show', $id)
            ->with('msg', 'Valor aplicado com sucesso ao investimento '.$investment->getAbbreviationAndName());
    }

    public function redeem(RedeemValuesRequest $request, $id)
    {
        $client = Client::find($id);
        $investment = Investment::find(decrypt($request->input('investment_id')));
        $valueToRedeem = $request->value_to_redeem;

        if (! $client->redeemValueFromInvestment($investment, $valueToRedeem)) {
            return redirect()->back()->withErrors([
                'value_to_redeem' => 'Não é possível resgatar um valor maior do que o aplicado ao investimento.',
            ]);
        }

        return redirect()->route('clients.show', $id)
            ->with('msg', 'Valor resgatado com sucesso do investimento '.$investment->getAbbreviationAndName());
    }
}
