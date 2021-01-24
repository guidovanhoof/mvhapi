<?php

namespace App\Http\Controllers;

use App\Http\Resources\WedstrijdResource;
use App\Models\Wedstrijd;
use App\Rules\InKalenderJaar;
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
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validData = $request->validate(
            [
                'kalender_id' => 'required|exists:kalenders,id',
                'datum' => [
                    'bail',
                    'required',
                    'date',
                    'unique:wedstrijden,datum',
                    new InKalenderJaar($request["kalender_id"])
                ],
                'nummer' => 'nullable|numeric|between:1,65535',
                'omschrijving' => 'required',
                'sponsor' => 'nullable',
                'aanvang' => 'required|date_format:H:i:s',
                'wedstrijdtype_id' => 'required|exists:wedstrijdtypes,id',
                'opmerkingen' => 'nullable',
            ]
        );

        return response()->json(
            new WedstrijdResource(Wedstrijd::create($validData)),
            201
        );
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
     * @param Request $request
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
