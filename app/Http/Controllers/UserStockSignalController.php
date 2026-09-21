<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserStockSignalController extends Controller
{
    /**
     * Get all stocks and indicate which stocks
     * are currently selected by the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $stocks = Stock::query()
            ->with('signals')
            ->orderBy('stock_code')
            ->get();

        $selectedStockCodes = $user->stocks()
            ->pluck('stocks.stock_code')
            ->toArray();

        $stocks = $stocks->map(function (Stock $stock) use ($selectedStockCodes) {

            return [
                'stock_code' => $stock->stock_code,
                'stock_name' => $stock->stock_name,
                'summary' => $stock->summary,

                'is_selected' => in_array(
                    $stock->stock_code,
                    $selectedStockCodes
                ),

                'signals' => $stock->signals->map(function ($signal) {

                    return [
                        'id' => $signal->id,
                        'signal' => $signal->signal,
                        'signal_strength' => $signal->signal_strength,
                        'description' => $signal->description,
                        'condition_1' => $signal->condition_1,
                        'condition_2' => $signal->condition_2,
                        'condition_3' => $signal->condition_3,
                    ];

                })->values(),
            ];

        })->values();

        return response()->json([
            'data' => $stocks,
        ]);
    }


    /**
     * Get only stocks selected by the authenticated user.
     */
    public function selected(Request $request): JsonResponse
    {
        $stocks = $request->user()
            ->stocks()
            ->with('signals')
            ->orderBy('stock_code')
            ->get();

        return response()->json([
            'data' => $stocks,
        ]);
    }


    /**
     * Replace all stock notification preferences.
     */
    public function update(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'stock_codes' => ['nullable', 'array'],

            'stock_codes.*' => [
                'string',
                'distinct',
                'exists:stocks,stock_code',
            ],
        ]);

        $user = $request->user();

        $user->stocks()->sync(
            $validated['stock_codes'] ?? []
        );

        if ($request->expectsJson()) {

            return response()->json([
                'message' => 'Stock notification preferences updated successfully.',

                'data' => $user->stocks()
                    ->with('signals')
                    ->orderBy('stock_code')
                    ->get(),
            ]);

        }

        return back()->with(
            'success',
            'Stock notification preferences updated successfully.'
        );
    }


    /**
     * Add one stock to the user's notification preferences.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'stock_code' => [
                'required',
                'string',
                'exists:stocks,stock_code',
            ],
        ]);

        $user = $request->user();

        $user->stocks()->syncWithoutDetaching([
            $validated['stock_code'],
        ]);

        return response()->json([
            'message' => 'Stock notification enabled successfully.',

            'data' => $user->stocks()
                ->with('signals')
                ->where(
                    'stocks.stock_code',
                    $validated['stock_code']
                )
                ->first(),
        ]);
    }


    /**
     * Remove one stock from the user's notification preferences.
     */
    public function destroy(
        Request $request,
        string $stockCode
    ): JsonResponse {
        $request->user()
            ->stocks()
            ->detach($stockCode);

        return response()->json([
            'message' => 'Stock notification disabled successfully.',
        ]);
    }
}
