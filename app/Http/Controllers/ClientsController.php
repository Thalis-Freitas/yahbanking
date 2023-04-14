<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientStoreRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;

class ClientsController extends Controller
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
        $input = $request->all();
        $path = $input['avatar']->store('avatars', 'public');
        $input['avatar'] = $path;

        Client::create($input);
        return redirect()->route('clients.index')
            ->with('msg', 'Cliente cadastrado com sucesso!');
    }

    public function show($id)
    {
        $client = Client::find($id);
        return view('clients.show', compact('client'));
    }

    public function edit($id)
    {
        $client = Client::find($id);
        return view('clients.edit', compact('client'));
    }

    public function update(ClientStoreRequest $request, $id): RedirectResponse
    {
        $input = $request->all();
        $client = Client::find($id);
        $client->update($input);
        return redirect()->route('clients.show', $id)
            ->with('msg', 'Dados atualizados com sucesso!');
    }

    public function destroy($id): RedirectResponse
    {
        Client::destroy($id);
        return redirect('clients')->with('msg', 'Cliente removido com sucesso!');
    }
}
