<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestmentStoreRequest;
use App\Http\Requests\InvestmentUpdateRequest;
use App\Models\Investment;

class InvestmentController extends Controller
{
    public function index()
    {
        $investments = Investment::latest()->paginate(14);

        return inertia('Home', compact('investments'));
    }

    public function create()
    {
        return inertia('Investments/CreateInvestment');
    }

    public function store(InvestmentStoreRequest $request)
    {
        $investment = Investment::create($request->validated());

        return redirect()->route('home')
            ->with('msg', 'Investimento cadastrado com sucesso!');
    }

    public function show(Investment $investment)
    {
        return inertia('Investments/ShowInvestment', compact('investment'));
    }

    public function edit(Investment $investment)
    {
        return inertia('Investments/EditInvestment', compact('investment'));
    }

    public function update(InvestmentUpdateRequest $request, Investment $investment)
    {
        $investment->update($request->validated());

        return redirect()->route('investments.show', $investment->id)
            ->with('msg', 'Dados atualizados com sucesso!');
    }

    public function destroy(Investment $investment)
    {
        $investment->deleteInvestment();

        return redirect()->route('home')
            ->with('msg', 'Investimento '.$investment->getAbbreviationUpper().' encerrado com sucesso!');
    }
}
