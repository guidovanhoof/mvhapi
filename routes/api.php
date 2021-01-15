<?php

use App\Http\Controllers\KalendersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(
    ["prefix" => "admin"],
    function () {
        Route::apiResource(
            "kalenders",
            KalendersController::class
        )
            ->parameters(["kalenders" => "jaar"])
            ->only('index', 'show');
    }
);

Route::middleware(
    'auth:sanctum'
)
    ->get(
        '/user',
        function (Request $request) {
            return $request->user();
        }
    );
