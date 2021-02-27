<?php

namespace App\Http\Controllers;

use App\Http\Resources\JeugdcategorieResource;
use App\Models\Jeugdcategorie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JeugdcategorieenController extends Controller
{
    /**
     * Tonen alle jeugdcategorieen.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            JeugdcategorieResource::collection(Jeugdcategorie::all())
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
     * @param  \App\Models\Jeugdcategorie  $jeugdcategorie
     * @return \Illuminate\Http\Response
     */
    public function show(Jeugdcategorie $jeugdcategorie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Jeugdcategorie  $jeugdcategorie
     * @return \Illuminate\Http\Response
     */
    public function edit(Jeugdcategorie $jeugdcategorie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Jeugdcategorie  $jeugdcategorie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jeugdcategorie $jeugdcategorie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Jeugdcategorie  $jeugdcategorie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jeugdcategorie $jeugdcategorie)
    {
        //
    }
}
