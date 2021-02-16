<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeelnemerResource;
use App\Models\Deelnemer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Helpers\nietGevondenResponse;

class DeelnemersController extends Controller
{
    /**
     * Ophalen alle deelnemers.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            DeelnemerResource::collection(Deelnemer::all()),
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
     * Ophalen één deelnemer.
     *
     * @param  $nummer
     * @return JsonResponse
     */
    public function show($nummer): JsonResponse
    {
        try {
            $deelnemer = Deelnemer::where("nummer", $nummer)->firstOrFail();
            return $this->deelnemerResourceResponse($deelnemer, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Deelnemer");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deelnemer  $deelnemer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deelnemer $deelnemer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deelnemer  $deelnemer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deelnemer $deelnemer)
    {
        //
    }

    /**
     * @param $deelnemer
     * @param int $status
     * @return JsonResponse
     */
    private function deelnemerResourceResponse($deelnemer, int $status): JsonResponse
    {
        return response()->json(
            new DeelnemerResource($deelnemer),
            $status
        );
    }
}
