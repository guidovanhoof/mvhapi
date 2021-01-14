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

//Route::resource(
//    'kalenders',
//    KalendersController::class
//)
//    ->middleware('auth:sanctum')
//    ->only('store');

Route::group(
    ["prefix" => "admin"],
    function () {
        Route::resource(
            "kalenders",
            KalendersController::class
        )
            ->only('index');
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
