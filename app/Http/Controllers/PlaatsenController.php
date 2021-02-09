<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaatsResource;
use App\Models\Plaats;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Helpers\nietGevondenResponse;

class PlaatsenController extends Controller
{
    /**
     * Tonen lijst met plaatsen.
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
     * Tonen van een specifieke plaats.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $plaats = Plaats::where("id", $id)->firstOrFail();
            return $this->plaatsResourceResponse($plaats, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Plaats");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Plaats $plaats
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
     * @param Plaats $plaats
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plaats $plaats)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Plaats $plaats
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plaats $plaats)
    {
        //
    }

    /**
     * @param Plaats $plaats
     * @param int $status
     * @return JsonResponse
     */
    private function plaatsResourceResponse(Plaats $plaats, int $status): JsonResponse
    {
        return response()->json(
            new PlaatsResource($plaats),
            $status
        );
    }
}
