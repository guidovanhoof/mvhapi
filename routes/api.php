<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\DeelnemersController;
use App\Http\Controllers\Api\GewichtenController;
use App\Http\Controllers\Api\JeugdcategorieenController;
use App\Http\Controllers\Api\KalendersController;
use App\Http\Controllers\Api\PlaatsdeelnemersController;
use App\Http\Controllers\Api\PlaatsenController;
use App\Http\Controllers\Api\ReeksController;
use App\Http\Controllers\Api\WedstrijddeelnemerJeugdcategorieenController;
use App\Http\Controllers\Api\WedstrijddeelnemersController;
use App\Http\Controllers\Api\WedstrijdenController;
use App\Http\Controllers\Api\WedstrijdtypesController;
use App\Http\Controllers\GetrokkenMatenController;
use App\Models\Wedstrijddeelnemer;
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
            ->middleware("auth:sanctum")
        ;

        Route::get(
            "kalenders/{jaar}/wedstrijden",
            [KalendersController::class, "wedstrijden"]
        )
            ->middleware("auth:sanctum")
        ;

        Route::apiResource(
            "wedstrijdtypes",
            WedstrijdtypesController::class
        )
            ->parameters(["wedstrijdtypes" => "id"])
            ->middleware("auth:sanctum")
        ;

        Route::apiResource(
            "wedstrijden",
            WedstrijdenController::class
        )
            ->parameters(["wedstrijden" => "datum"])
            ->middleware("auth:sanctum")
        ;

        Route::get(
            "wedstrijden/{datum}/reeksen",
            [WedstrijdenController::class, "reeksen"]
        )
            ->middleware("auth:sanctum")
        ;

        Route::get(
            "wedstrijden/{datum}/deelnemers",
            [WedstrijdenController::class, "deelnemers"]
        )
            ->middleware("auth:sanctum")
        ;

        Route::apiResource(
            "reeksen",
            ReeksController::class
        )
            ->parameters(["reeksen" => "id"])
            ->middleware("auth:sanctum")
        ;

        Route::get(
            "reeksen/{id}/plaatsen",
            [ReeksController::class, "plaatsen"]
        )
            ->middleware("auth:sanctum")
        ;

        Route::apiResource(
            "plaatsen",
            PlaatsenController::class
        )
            ->parameters(["plaatsen" => "id"])
            ->middleware("auth:sanctum")
        ;

        Route::get(
            "plaatsen/{id}/gewichten",
            [PlaatsenController::class, "gewichten"]
        )
            ->middleware("auth:sanctum")
        ;

        Route::get(
            "plaatsen/{id}/deelnemers",
            [PlaatsenController::class, "deelnemers"]
        )
            ->middleware("auth:sanctum")
        ;

        Route::apiResource(
            "gewichten",
            GewichtenController::class
        )
            ->parameters(["gewichten" => "id"])
            ->middleware("auth:sanctum")
        ;

        Route::apiResource(
            "deelnemers",
            DeelnemersController::class
        )
            ->parameters(["deelnemers" => "nummer"])
            ->middleware("auth:sanctum")
        ;

        Route::apiResource(
            "wedstrijddeelnemers",
            WedstrijddeelnemersController::class
        )
            ->parameters(["wedstrijddeelnemers" => "id"])
            ->middleware("auth:sanctum")
        ;

        Route::get(
            "wedstrijddeelnemers/{id}/jeugdcategorie",
            [WedstrijddeelnemersController::class, "jeugdcategorie"]
        )
            ->middleware("auth:sanctum")
        ;

        Route::apiResource(
            "plaatsdeelnemers",
            PlaatsdeelnemersController::class
        )
            ->parameters(["plaatsdeelnemers" => "id"])
            ->middleware("auth:sanctum")
        ;

        Route::apiResource(
            "jeugdcategorieen",
            JeugdcategorieenController::class
        )
            ->parameters(["jeugdcategorieen" => "id"])
            ->middleware("auth:sanctum")
        ;

        Route::apiResource(
            "wedstrijddeelnemerjeugdcategorieen",
            WedstrijddeelnemerJeugdcategorieenController::class
        )
            ->parameters(["wedstrijddeelnemerjeugdcategorieen" => "id"])
            ->middleware("auth:sanctum")
        ;

        Route::apiResource(
            "getrokkenmaten",
            GetrokkenMatenController::class
        )
            ->parameters(["getrokkenmaten" => "id"])
            ->middleware("auth:sanctum")
        ;
    }
);

Route::post(
    'token/create',
    [ApiAuthController::class, 'createToken']
);

Route::fallback(
    function () {
        return response()->json("Pagina niet gevonden!", 404);
    }
);
