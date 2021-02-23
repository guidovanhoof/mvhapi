<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaatsdeelnemerResource;
use  App\Models\Plaatsdeelnemer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaatsdeelnemersController extends Controller
{
    /**
     * Ophalen alle plaatsdeelnemers.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            PlaatsdeelnemerResource::collection(Plaatsdeelnemer::all()),
            200
        );
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plaatsdeelnemer  $plaatsdeelnemer
     * @return \Illuminate\Http\Response
     */
    public function show(Plaatsdeelnemer $plaatsdeelnemer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plaatsdeelnemer  $plaatsdeelnemer
     * @return \Illuminate\Http\Response
     */
    public function edit(Plaatsdeelnemer $plaatsdeelnemer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plaatsdeelnemer  $plaatsdeelnemer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plaatsdeelnemer $plaatsdeelnemer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plaatsdeelnemer  $plaatsdeelnemer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plaatsdeelnemer $plaatsdeelnemer)
    {
        //
    }
}
