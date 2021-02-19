<?php

namespace App\Http\Controllers;

use App\Http\Resources\WedstrijddeelnemerResource;
use App\Models\Wedstrijddeelnemer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Helpers\nietGevondenResponse;

class WedstrijddeelnemersController extends Controller
{
    /**
     * Ophalen alle wedstrijddeelnemers.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            WedstrijddeelnemerResource::collection(Wedstrijddeelnemer::all()),
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
     * Ophalen één wedstrijddeelnemer.
     *
     * @param  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $wedstrijddeelnemer = Wedstrijddeelnemer::where("id", $id)->firstOrFail();
            return $this->wedstrijddeelnemerResourceResponse($wedstrijddeelnemer, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Wedstrijddeelnemer");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wedstrijddeelnemer  $wedstrijddeelnemer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wedstrijddeelnemer $wedstrijddeelnemer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wedstrijddeelnemer  $wedstrijddeelnemer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wedstrijddeelnemer $wedstrijddeelnemer)
    {
        //
    }

    /**
     * @param $wedstrijddeelnemer
     * @param int $status
     * @return JsonResponse
     */
    private function wedstrijddeelnemerResourceResponse($wedstrijddeelnemer, int $status): JsonResponse
    {
        return response()->json(
          new WedstrijddeelnemerResource($wedstrijddeelnemer),
          $status
        );
    }
}
