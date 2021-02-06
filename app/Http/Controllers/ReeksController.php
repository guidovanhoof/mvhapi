<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReeksResource;
use App\Models\Reeks;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function App\Helpers\nietGevondenResponse;

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
     * Geef één specifieke reeks terug.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $reeks = Reeks::where("id", $id)->firstOrFail();
            return $this->reeksResourceResponse($reeks, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Reeks");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Reeks $reeks
     * @return Response
     */
    public function edit(Reeks $reeks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Reeks $reeks
     * @return Response
     */
    public function update(Request $request, Reeks $reeks)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Reeks $reeks
     * @return Response
     */
    public function destroy(Reeks $reeks)
    {
        //
    }

    /**
     * @param Reeks $reeks
     * @param int $status
     * @return JsonResponse
     */
    private function reeksResourceResponse(Reeks $reeks, int $status): JsonResponse
    {
        return response()->json(
            new ReeksResource($reeks),
            $status
        );
    }
}
