<?php

namespace App\Http\Controllers;

use App\Http\Resources\KalenderResource;
use App\Models\Kalender;
use http\Env\Response;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        //return response()->json(Kalender::create($validData)->toArray(), 201);
        return response()->json(
            new KalenderResource(Kalender::create($validData)),
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kalender  $kalender
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kalender  $kalender
     * @return \Illuminate\Http\Response
     */
    public function edit(Kalender $kalender)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kalender  $kalender
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kalender $kalender)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kalender  $kalender
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kalender $kalender)
    {
        //
    }
}
