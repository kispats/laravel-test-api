<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoneyController;


Route::get('/test', function (Request $request) {

    $param1 = $request->query('param1', '');
    $param2 = $request->query('param2', '');

    $prefix = env('USER_DEFINED_TEXT', 'DEFAULT');

    return response()->json([
        'result' => $prefix . ' - ' . $param1 . ' ' . $param2
    ]);
});

Route::get('/number-to-words', [MoneyController::class, 'convert']);

// GET /api/number-to-words?amount=1234.56

