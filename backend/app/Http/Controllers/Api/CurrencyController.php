<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    /**
     * Obtener todas las monedas activas
     */
    public function index(): JsonResponse
    {
        $currencies = Currency::active()
            ->select('id', 'code', 'name', 'symbol')
            ->orderBy('name')
            ->get();

        return response()->json([
            'currencies' => $currencies
        ]);
    }
}