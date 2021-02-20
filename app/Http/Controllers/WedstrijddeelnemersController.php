<?php

namespace App\Http\Controllers;

use App\Http\Resources\WedstrijddeelnemerResource;
use App\Models\Wedstrijddeelnemer;
use App\Rules\DeelnemerUniekPerWedstrijd;
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
     * Bewaren nieuwe deelnemer.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validData = $this->valideerWedstrijddeelnemer($request, new Wedstrijddeelnemer());

        return $this->wedstrijddeelnemerResourceResponse(
            Wedstrijddeelnemer::create($validData),201
        );
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
     * @param Request $request
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

    private function valideerWedstrijddeelnemer(Request $request, Wedstrijddeelnemer $wedstrijddeelnemer)
    {
        return $request->validate(
            [
                "wedstrijd_id" => "bail|required|exists:wedstrijden,id",
                "deelnemer_id" => [
                    "bail",
                    "required",
                    "exists:deelnemers,id",
                    new DeelnemerUniekPerWedstrijd($request["wedstrijd_id"], $wedstrijddeelnemer->id)
                ],
                "is_gediskwalificeerd" => "bail|required|numeric|between:0,1",
                "opmerkingen" => "bail|nullable",
            ]
        );
    }
}
