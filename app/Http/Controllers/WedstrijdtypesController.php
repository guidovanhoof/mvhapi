<?php

namespace App\Http\Controllers;

use App\Http\Resources\WedstrijdtypeResource;
use App\Models\Wedstrijdtype;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WedstrijdtypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return WedstrijdtypeResource::collection(Wedstrijdtype::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function store(Request $request)
    {
        $validData = $request->validate(
            [
                'omschrijving' => 'required|unique:wedstrijdtypes,omschrijving',
            ]
        );

        return response()->json(
            new WedstrijdtypeResource(Wedstrijdtype::create($validData)),
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return WedstrijdtypeResource|JsonResponse
     */
    public function show( $id)
    {
        try {
            $wedstrijdtype = Wedstrijdtype::where("id", $id)->firstOrFail();
            return new WedstrijdtypeResource($wedstrijdtype);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json(
                ["message" => "Wedstrijdtype niet gevonden!"],
                404
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse|Response
     */
    public function update(Request $request, $id)
    {
        $validData = $request->validate(
            [
                'omschrijving' => 'required|unique:wedstrijdtypes,omschrijving,' . $id . ',id',
            ]
        );

        try {
            $wedstrijdtype = Wedstrijdtype::where("id", $id)->firstOrFail();
            $wedstrijdtype->omschrijving = $validData["omschrijving"];
            $wedstrijdtype->save();
            return response()->json(
                new WedstrijdtypeResource($wedstrijdtype),
                200
            );
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json(
                ["message" => "Wedstrijdtype niet gevonden!"],
                404
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wedstrijdtype  $wedstrijdtype
     * @return Response
     */
    public function destroy(Wedstrijdtype $wedstrijdtype)
    {
        //
    }
}
