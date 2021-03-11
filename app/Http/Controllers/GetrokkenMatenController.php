<?php

namespace App\Http\Controllers;

use App\Http\Resources\GetrokkenMaatResource;
use App\Models\GetrokkenMaat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetrokkenMatenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            GetrokkenMaatResource::collection(GetrokkenMaat::all())
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
     * @param  \App\Models\GetrokkenMaat  $getrokkenMaat
     * @return \Illuminate\Http\Response
     */
    public function show(GetrokkenMaat $getrokkenMaat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GetrokkenMaat  $getrokkenMaat
     * @return \Illuminate\Http\Response
     */
    public function edit(GetrokkenMaat $getrokkenMaat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GetrokkenMaat  $getrokkenMaat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GetrokkenMaat $getrokkenMaat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GetrokkenMaat  $getrokkenMaat
     * @return \Illuminate\Http\Response
     */
    public function destroy(GetrokkenMaat $getrokkenMaat)
    {
        //
    }
}
