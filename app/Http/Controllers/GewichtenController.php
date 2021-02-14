<?php

namespace App\Http\Controllers;

use App\Http\Resources\GewichtResource;
use App\Models\Gewicht;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GewichtenController extends Controller
{
    /**
     * Ophalen alle gewichten..
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            GewichtResource::collection(Gewicht::all())
        );
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
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gewicht  $gewicht
     * @return Response
     */
    public function show(Gewicht $gewicht)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gewicht  $gewicht
     * @return Response
     */
    public function edit(Gewicht $gewicht)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gewicht  $gewicht
     * @return Response
     */
    public function update(Request $request, Gewicht $gewicht)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gewicht  $gewicht
     * @return Response
     */
    public function destroy(Gewicht $gewicht)
    {
        //
    }
}
