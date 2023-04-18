<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientStoreRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\ClientInvestimentStoreRequest;
use App\Models\Client;
use App\Models\Investiment;

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
        if(array_key_exists('avatar', $input)){
            $input['avatar'] = $input['avatar']->store('avatars', 'public');
        };

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
        if(array_key_exists('avatar', $input)){
            $input['avatar'] = $input['avatar']->store('avatars', 'public');
        };
        $client = Client::find($id);

        $client->update($input);

        return redirect()->route('clients.show', $id)
            ->with('msg', 'Dados atualizados com sucesso!');
    }

    public function destroy($id)
    {
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
        $invested_value = $request->invested_value;

        if ($invested_value > $client->uninvested_value) {
            return redirect()->back()->withErrors([
                'invested_value' => 'Não é possível aplicar um valor maior do que o disponível (valor não investido).'
            ]);
        }

        $client->invested_value += $invested_value;
        $client->uninvested_value -= $invested_value;

        $client->investiments()->attach([
            $investiment->id => ['invested_value' => $invested_value]
        ]);

        $client->save();

        return redirect()->route('clients.show', $id)
            ->with('msg', 'Cliente vinculado com sucesso ao Investimento ' . $investiment->getAbbreviationAndName());
    }
}
