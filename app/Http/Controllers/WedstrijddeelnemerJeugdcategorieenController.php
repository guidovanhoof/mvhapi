<?php

namespace App\Http\Controllers;

use App\Http\Resources\WedstrijddeelnemerJeugdcategorieResource;
use App\Models\WedstrijddeelnemerJeugdcategorie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WedstrijddeelnemerJeugdcategorieenController extends Controller
{
    /**
     * Tonen alle wedstrijddeelnemers met een jeugdcategorie.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            WedstrijddeelnemerJeugdcategorieResource::collection(WedstrijddeelnemerJeugdcategorie::all())
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
     * @param  \App\Models\WedstrijddeelnemerJeugdcategorie  $wedstrijddeelnemerJeugdcategorie
     * @return \Illuminate\Http\Response
     */
    public function show(WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WedstrijddeelnemerJeugdcategorie  $wedstrijddeelnemerJeugdcategorie
     * @return \Illuminate\Http\Response
     */
    public function edit(WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WedstrijddeelnemerJeugdcategorie  $wedstrijddeelnemerJeugdcategorie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WedstrijddeelnemerJeugdcategorie  $wedstrijddeelnemerJeugdcategorie
     * @return \Illuminate\Http\Response
     */
    public function destroy(WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie)
    {
        //
    }
}
