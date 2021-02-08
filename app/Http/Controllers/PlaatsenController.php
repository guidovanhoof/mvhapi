<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaatsResource;
use App\Models\Plaats;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlaatsenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            PlaatsResource::collection(Plaats::all()->sortBy(["reeks_id", "nummer"])),
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
     * @param  \App\Models\Plaats  $plaats
     * @return \Illuminate\Http\Response
     */
    public function show(Plaats $plaats)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plaats  $plaats
     * @return \Illuminate\Http\Response
     */
    public function edit(Plaats $plaats)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plaats  $plaats
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plaats $plaats)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plaats  $plaats
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plaats $plaats)
    {
        //
    }
}
