<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestimentStoreRequest;
use App\Http\Requests\InvestimentUpdateRequest;
use App\Models\Investiment;

class InvestimentController extends Controller
{
    public function index()
    {
        $investiments = Investiment::paginate(10);

        return view('home', compact('investiments'));
    }

    public function create()
    {
        return view('investiments.create');
    }

    public function store(InvestimentStoreRequest $request)
    {
        $input = $request->all();

        $investiment = Investiment::create($input);

        return redirect()->route('investiments.show', $investiment->id)
            ->with('msg', 'Investimento cadastrado com sucesso!');
    }

    public function show($id)
    {
        $investiment = Investiment::find($id);

        return view('investiments.show', compact('investiment'));
    }

    public function edit($id)
    {
        $investiment = Investiment::find($id);

        return view('investiments.edit', compact('investiment'));
    }

    public function update(InvestimentUpdateRequest $request, $id)
    {
        $input = $request->all();
        $investiment = Investiment::find($id);
        $investiment->update($input);

        return redirect()->route('investiments.show', $id)
            ->with('msg', 'Dados atualizados com sucesso!');
    }

    public function destroy($id)
    {
        $investiment = Investiment::find($id);

        $investiment->deleteInvestiment($id);

        return redirect('/')->with('msg', 'Investimento encerrado com sucesso!');
    }
}
