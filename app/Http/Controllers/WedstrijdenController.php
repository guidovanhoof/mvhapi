<?php

namespace App\Http\Controllers;

use App\Http\Resources\WedstrijdResource;
use App\Models\Wedstrijd;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WedstrijdenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return WedstrijdResource::collection(Wedstrijd::all());
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
     * @param  \App\Models\Wedstrijd  $wedstrijd
     * @return \Illuminate\Http\Response
     */
    public function show(Wedstrijd $wedstrijd)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wedstrijd  $wedstrijd
     * @return \Illuminate\Http\Response
     */
    public function edit(Wedstrijd $wedstrijd)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wedstrijd  $wedstrijd
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wedstrijd $wedstrijd)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wedstrijd  $wedstrijd
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wedstrijd $wedstrijd)
    {
        //
    }
}
