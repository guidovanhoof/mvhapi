<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaatsdeelnemerResource;
use App\Models\Plaats;
use App\Models\Plaatsdeelnemer;
use App\Rules\DeelnemerUniekPerPlaats;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Helpers\nietGevondenResponse;

class PlaatsdeelnemersController extends Controller
{
    /**
     * Ophalen alle plaatsdeelnemers.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            PlaatsdeelnemerResource::collection(Plaatsdeelnemer::all()),
            200
        );
    }

    /**
     * Bewaren nieuwe plaatsdeelnemer..
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validData = $this->valideerPlaatsdeelnemer($request, new Plaatsdeelnemer());
        return $this->plaatsdeelnemerResourceResponse(Plaatsdeelnemer::create($validData), 201);
    }

    /**
     * Ophalen één plaatsdeelnemer.
     *
     * @param  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $plaatsdeelnemer = Plaatsdeelnemer::where("id", $id)->firstOrFail();
            return $this->plaatsdeelnemerResourceResponse($plaatsdeelnemer, 200);
        } catch(ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Plaatsdeelnemer");
        }
    }

    /**
     * Wijzigen bestaande plaatsdeelnemer.
     *
     * @param Request $request
     * @param  \App\Models\Plaatsdeelnemer  $plaatsdeelnemer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plaatsdeelnemer $plaatsdeelnemer)
    {
        //
    }

    /**
     * Verwijderen bestaande plaatsdeelnemer.
     *
     * @param  \App\Models\Plaatsdeelnemer  $plaatsdeelnemer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plaatsdeelnemer $plaatsdeelnemer)
    {
        //
    }

    /**
     * @param Plaatsdeelnemer $plaatsdeelnemer
     * @param int $status
     * @return JsonResponse
     */
    private function plaatsdeelnemerResourceResponse(Plaatsdeelnemer $plaatsdeelnemer, int $status): JsonResponse
    {
        return response()->json(
            new PlaatsdeelnemerResource($plaatsdeelnemer),
            $status
        );
    }

    /**
     * @param Request $request
     * @param Plaatsdeelnemer $plaatsdeelnemer
     * @return array
     */
    private function valideerPlaatsdeelnemer(Request $request, Plaatsdeelnemer  $plaatsdeelnemer): array
    {
        return $request->validate(
            [
              'plaats_id' => 'bail|required|exists:plaatsen,id',
              'wedstrijddeelnemer_id' => [
                  'bail',
                  'required',
                  'exists:wedstrijddeelnemers,id',
                  new DeelnemerUniekPerPlaats($request["plaats_id"], $plaatsdeelnemer->id)
              ],
              'is_weger' => 'bail|required|numeric|between:0,1',
          ]
        );
    }
}
