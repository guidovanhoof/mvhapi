<?php

namespace App\Http\Controllers;

use App\Http\Resources\WedstrijdResource;
use App\Models\Wedstrijd;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WedstrijdenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return WedstrijdResource::collection(Wedstrijd::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param $datum
     * @return WedstrijdResource|JsonResponse
     */
    public function show($datum)
    {
        try {
            $wedstrijd = Wedstrijd::where("datum", $datum)->firstOrFail();
            return new WedstrijdResource($wedstrijd);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json(
                ["message" => "Wedstrijd niet gevonden!"],
                404
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wedstrijd  $wedstrijd
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wedstrijd $wedstrijd)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wedstrijd  $wedstrijd
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wedstrijd $wedstrijd)
    {
        //
    }
}
