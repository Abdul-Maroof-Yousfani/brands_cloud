<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    /**
     * List all currencies
     */
    public function index()
    {
        $currencies = (new Currency())->setConnection('mysql2')
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();

        return view('currency.index', compact('currencies'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('currency.create');
    }

    /**
     * Store new currency
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'rate'     => 'required|numeric|min:0',
        ]);

        $currency = new Currency();
        $currency->setConnection('mysql2');

        // Check duplicate by name
        $exists = $currency->where('status', 1)
            ->where('name', $request->name)
            ->count();

        if ($exists > 0) {
            return redirect()->route('currency.create')
                ->with('error', 'Currency "' . $request->name . '" already exists.')
                ->withInput();
        }

        $currency->name    = strtoupper($request->name);
        $currency->rate    = $request->rate;
        $currency->status  = 1;
        $currency->username = Auth::user()->name;
        $currency->date    = date('Y-m-d');
        $currency->save();

        return redirect()->route('currency.index')
            ->with('success', 'Currency "' . $currency->name . '" added successfully.');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $currency = (new Currency())->setConnection('mysql2')->findOrFail($id);
        return view('currency.edit', compact('currency'));
    }

    /**
     * Update currency
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'rate' => 'required|numeric|min:0',
        ]);

        $currency = (new Currency())->setConnection('mysql2')->findOrFail($id);
        $currency->name    = strtoupper($request->name);
        $currency->rate    = $request->rate;
        $currency->username = Auth::user()->name;
        $currency->date    = date('Y-m-d');
        $currency->save();

        return redirect()->route('currency.index')
            ->with('success', 'Currency "' . $currency->name . '" updated successfully.');
    }

    /**
     * Soft delete (set status = 0)
     */
    public function destroy($id)
    {
        $currency = (new Currency())->setConnection('mysql2')->findOrFail($id);
        $currency->status = 0;
        $currency->save();

        return redirect()->route('currency.index')
            ->with('success', 'Currency deleted successfully.');
    }
}
