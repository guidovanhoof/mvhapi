<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeelnemerResource;
use App\Models\Deelnemer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeelnemersController extends Controller
{
    /**
     * Ophalen alle deelnemers.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            DeelnemerResource::collection(Deelnemer::all()),
            200
        );
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
     * @param  \App\Models\Deelnemer  $deelnemer
     * @return \Illuminate\Http\Response
     */
    public function show(Deelnemer $deelnemer)
    {
        //
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deelnemer  $deelnemer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deelnemer $deelnemer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deelnemer  $deelnemer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deelnemer $deelnemer)
    {
        //
    }
}
