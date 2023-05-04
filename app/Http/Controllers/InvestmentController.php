<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestmentStoreRequest;
use App\Http\Requests\InvestmentUpdateRequest;
use App\Models\Investment;

class InvestmentController extends Controller
{
    public function index()
    {
        $investments = Investment::paginate(10);

        return view('home', compact('investments'));
    }

    public function create()
    {
        return view('investments.create');
    }

    public function store(InvestmentStoreRequest $request)
    {
        $input = $request->all();

        $investment = Investment::create($input);

        return redirect()->route('investments.show', $investment->id)
            ->with('msg', 'Investmento cadastrado com sucesso!');
    }

    public function show($id)
    {
        $investment = Investment::find($id);

        return view('investments.show', compact('investment'));
    }

    public function edit($id)
    {
        $investment = Investment::find($id);

        return view('investments.edit', compact('investment'));
    }

    public function update(InvestmentUpdateRequest $request, $id)
    {
        $input = $request->all();
        $investment = Investment::find($id);
        $investment->update($input);

        return redirect()->route('investments.show', $id)
            ->with('msg', 'Dados atualizados com sucesso!');
    }

    public function destroy($id)
    {
        $investment = Investment::find($id);

        $investment->deleteInvestment($id);

        return redirect('/')->with('msg', 'Investmento encerrado com sucesso!');
    }
}
