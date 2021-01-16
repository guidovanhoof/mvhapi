<?php

namespace App\Http\Controllers;

use App\Http\Resources\KalenderResource;
use App\Models\Kalender;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class KalendersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return KalenderResource::collection(Kalender::all()->sortByDesc("jaar"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
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
     * @return KalenderResource|\Illuminate\Http\JsonResponse
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
     * @param \Illuminate\Http\Request $request
     * @param $jaar
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $jaar)
    {
        $validData = $request->validate(
            [
                'jaar' => 'required|unique:kalenders,jaar,' . $jaar . ',jaar',
                'opmerkingen' => 'nullable'
            ]
        );

        try {
            $kalender = Kalender::where("jaar", $jaar)->firstOrFail();
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
     * @return \Illuminate\Http\JsonResponse
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
