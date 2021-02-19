<?php

namespace App\Http\Controllers;

use App\Http\Resources\WedstrijddeelnemerResource;
use App\Models\Wedstrijddeelnemer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WedstrijddeelnemersController extends Controller
{
    /**
     * Ophalen alle wedstrijddeelnemers.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            WedstrijddeelnemerResource::collection(Wedstrijddeelnemer::all()),
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
     * @param  \App\Models\Wedstrijddeelnemer  $wedstrijddeelnemer
     * @return \Illuminate\Http\Response
     */
    public function show(Wedstrijddeelnemer $wedstrijddeelnemer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wedstrijddeelnemer  $wedstrijddeelnemer
     * @return \Illuminate\Http\Response
     */
    public function edit(Wedstrijddeelnemer $wedstrijddeelnemer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wedstrijddeelnemer  $wedstrijddeelnemer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wedstrijddeelnemer $wedstrijddeelnemer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wedstrijddeelnemer  $wedstrijddeelnemer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wedstrijddeelnemer $wedstrijddeelnemer)
    {
        //
    }
}
