<?php

namespace App\Http\Controllers;

use App\Http\Resources\KalenderResource;
use App\Models\Kalender;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class KalendersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return KalenderResource::collection(Kalender::all()->sortByDesc("jaar"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validData = $request->validate(
            [
                'jaar' => 'required|unique:kalenders,jaar',
                'opmerkingen' => 'nullable'
            ]
        );

        return response()->json(
            new KalenderResource(Kalender::create($validData)),
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param $jaar
     * @return KalenderResource|JsonResponse
     */
    public function show($jaar)
    {
        try {
            $kalender = Kalender::where("jaar", $jaar)->firstOrFail();
            return new KalenderResource($kalender);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json(
                ["message" => "Kalender niet gevonden!"],
                404
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $jaar
     * @return JsonResponse
     */
    public function update(Request $request, $jaar)
    {
        try {
            $kalender = Kalender::where("jaar", $jaar)->firstOrFail();
            $validData = $request->validate(
                [
                    'jaar' => 'required|unique:kalenders,jaar,' . $jaar . ',jaar',
                    'opmerkingen' => 'nullable'
                ]
            );
            $kalender->jaar = $validData["jaar"];
            $kalender->opmerkingen = $validData["opmerkingen"];
            $kalender->save();
            return response()->json(
                new KalenderResource($kalender),
                200
            );
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json(
                ["message" => "Kalender niet gevonden!"],
                404
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kalender  $kalender
     * @return JsonResponse
     */
    public function destroy($jaar)
    {
        try {
            $kalender = Kalender::where("jaar", $jaar)->firstOrFail();
            $kalender->delete();
            return response()->json(
                ["message" => "Kalender verwijderd!"],
                200
            );
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json(
                ["message" => "Kalender niet gevonden!"],
                404
            );
        }
    }
}
