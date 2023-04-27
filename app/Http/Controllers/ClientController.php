<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyValuesRequest;
use App\Http\Requests\ClientInvestimentStoreRequest;
use App\Http\Requests\ClientStoreRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\RedeemValuesRequest;
use App\Models\Client;
use App\Models\Investiment;
use Illuminate\Support\Facades\DB;

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
        $investiments = Investiment::whereNotIn('id', function ($query) use ($id) {
            $query->select('investiment_id')
                  ->from('client_investiment')
                  ->where('client_id', '=', $id);
        })->orderBy('abbreviation')->get();

        $client = Client::find($id);

        return view('clients.show', compact('client', 'investiments'));
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
        Client::find($id)->investiments()->detach();

        Client::destroy($id);

        return redirect('clients')->with('msg', 'Cliente removido com sucesso!');
    }

    public function deposit(DepositRequest $request, $id)
    {
        $client = Client::find($id);
        $uninvestedValue = $client->uninvested_value + $request->uninvested_value;
        $client->update(['uninvested_value' => $uninvestedValue]);

        return redirect()->route('clients.show', $id)
            ->with('msg', 'Valor depositado com sucesso!');
    }

    public function investiment(ClientInvestimentStoreRequest $request, $id)
    {
        $data = json_decode($request->investiment);
        $investiment = Investiment::find($data->id);
        $client = Client::find($id);
        $investedValue = $request->invested_value;

        if ($investedValue > $client->uninvested_value) {
            return redirect()->back()->withErrors([
                'invested_value' => 'Não é possível aplicar um valor maior do que o disponível (valor não investido).',
            ]);
        }

        $client->invested_value += $investedValue;
        $client->uninvested_value -= $investedValue;

        $client->investiments()->attach([
            $investiment->id => ['invested_value' => $investedValue],
        ]);

        $client->save();

        return redirect()->route('clients.show', $id)
            ->with('msg', 'Cliente vinculado com sucesso ao Investimento '.$investiment->getAbbreviationAndName());
    }

    public function apply(ApplyValuesRequest $request, $id)
    {
        $client = Client::find($id);
        $investiment = Investiment::find(decrypt($request->input('investiment_id')));
        $valueToApply = $request->value_to_apply;

        if ($valueToApply > $client->uninvested_value) {
            return redirect()->back()->withErrors([
                'value_to_apply' => 'Não é possível aplicar um valor maior do que o disponível (valor não investido).',
            ]);
        }

        $client->invested_value += $valueToApply;
        $client->uninvested_value -= $valueToApply;
        $client->save();

        $investiment->clients()->syncWithoutDetaching([
            $client->id => ['invested_value' => DB::raw('invested_value + '.$valueToApply)],
        ]);

        return redirect()->route('clients.show', $id)
            ->with('msg', 'Valor aplicado com sucesso ao investimento '.$investiment->getAbbreviationAndName());
    }

    public function redeem(RedeemValuesRequest $request, $id)
    {
        $client = Client::find($id);
        $investiment = Investiment::find(decrypt($request->input('investiment_id')));
        $investedValue = $client->investiments->find($investiment->id)->pivot->invested_value;
        $valueToRedeem = $request->value_to_redeem;

        if ($valueToRedeem > $investedValue) {
            return redirect()->back()->withErrors([
                'value_to_redeem' => 'Não é possível resgatar um valor maior do que o aplicado ao investimento.',
            ]);
        }

        $updatedInvestedValue = $investedValue - $valueToRedeem;
        $investiment->clients()->updateExistingPivot($client->id, [
            'invested_value' => DB::raw('invested_value - '.$valueToRedeem),
        ]);

        $client->uninvested_value += $valueToRedeem;
        $client->invested_value -= $valueToRedeem;
        $client->save();

        if ($updatedInvestedValue == 0) {
            $investiment->clients()->detach($client->id);
        }

        return redirect()->route('clients.show', $id)
            ->with('msg', 'Valor resgatado com sucesso do investimento '.$investiment->getAbbreviationAndName());
    }
}
