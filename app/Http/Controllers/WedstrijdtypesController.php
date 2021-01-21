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
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wedstrijdtype  $wedstrijdtype
     * @return Response
     */
    public function edit(Wedstrijdtype $wedstrijdtype)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Models\Wedstrijdtype  $wedstrijdtype
     * @return Response
     */
    public function update(Request $request, Wedstrijdtype $wedstrijdtype)
    {
        //
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
