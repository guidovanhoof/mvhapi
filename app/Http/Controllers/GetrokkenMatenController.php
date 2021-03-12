<?php

namespace App\Http\Controllers;

use App\Http\Resources\GetrokkenMaatResource;
use App\Models\GetrokkenMaat;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Helpers\nietGevondenResponse;

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
     * Tonen van één getrokken maat.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $getrokkenMaat = GetrokkenMaat::where('id', $id)->firstOrFail();
            return $this->getrokkenMaatResourceResponse($getrokkenMaat);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse('GetrokkenMaat');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param GetrokkenMaat $getrokkenMaat
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
     * @param GetrokkenMaat $getrokkenMaat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GetrokkenMaat $getrokkenMaat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param GetrokkenMaat $getrokkenMaat
     * @return \Illuminate\Http\Response
     */
    public function destroy(GetrokkenMaat $getrokkenMaat)
    {
        //
    }

    /**
     * @param GetrokkenMaat $getrokkenMaat
     * @return JsonResponse
     */
    private function getrokkenMaatResourceResponse(GetrokkenMaat $getrokkenMaat): JsonResponse
    {
        return response()->json(
            new GetrokkenMaatResource($getrokkenMaat)
        );
    }
}
