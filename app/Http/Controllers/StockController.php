<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = Stock::all();

        return response()->json($stocks);
    }


    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        return response()->json($stock);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'stock_name' => 'sometimes|string|max:255',
            'summary' => 'sometimes|nullable|string',
            'summary_updated' => 'sometimes|nullable|date',
        ]);

        $stock->update($validated);

        return response()->json($stock);
    }

  
}