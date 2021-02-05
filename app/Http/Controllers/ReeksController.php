<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReeksResource;
use App\Models\Reeks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReeksController extends Controller
{
    /**
     * Geef een lijst van reeksen terug.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            ReeksResource::collection(
                Reeks::all()->sortBy(["wedstrijd_id", "nummer"])
            )
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
     * @param  \App\Models\Reeks  $reeks
     * @return \Illuminate\Http\Response
     */
    public function show(Reeks $reeks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reeks  $reeks
     * @return \Illuminate\Http\Response
     */
    public function edit(Reeks $reeks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reeks  $reeks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reeks $reeks)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reeks  $reeks
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reeks $reeks)
    {
        //
    }
}
