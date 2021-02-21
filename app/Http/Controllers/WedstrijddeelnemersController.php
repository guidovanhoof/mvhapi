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
     * Bewaren nieuwe wedstrijddeelnemer.
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
     * Wijzigen bestaande wedstrijddeelnemer..
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $wedstrijddeelnemer = Wedstrijddeelnemer::where("id", $id)->firstOrFail();
            $wedstrijddeelnemer->update($this->valideerWedstrijddeelnemer($request, $wedstrijddeelnemer));
            return $this->wedstrijddeelnemerResourceResponse($wedstrijddeelnemer, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Wedstrijddeelnemer");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Wedstrijddeelnemer $wedstrijddeelnemer
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

    /**
     * @param Request $request
     * @param Wedstrijddeelnemer $wedstrijddeelnemer
     * @return array
     */
    private function valideerWedstrijddeelnemer(Request $request, Wedstrijddeelnemer $wedstrijddeelnemer): array
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
