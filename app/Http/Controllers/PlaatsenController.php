<?php

namespace App\Http\Controllers;

use App\Http\Resources\GewichtResource;
use App\Http\Resources\PlaatsdeelnemerResource;
use App\Http\Resources\PlaatsResource;
use App\Models\Plaats;
use App\Rules\NummerUniekPerReeks;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use function App\Helpers\nietGevondenResponse;
use function App\Helpers\nietVerwijderdResponse;
use function App\Helpers\verwijderdResponse;

class PlaatsenController extends Controller
{
    /**
     * Ophalen alle plaatsen.
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
     * Bewaren nieuwe plaats.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validData = $this->valideerPlaats($request, new Plaats());

        return $this->plaatsResourceResponse(Plaats::create($validData), 201);
    }

    /**
     * Ophalen één plaats.
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
     * Wijzigen van een bestaande plaats.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $plaats = Plaats::where("id", $id)->firstOrFail();
            $plaats->update(
                $this->valideerPlaats($request, $plaats)
            );
            return $this->plaatsResourceResponse($plaats, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Plaats");
        }
    }

    /**
     * Verwijderen bestaande plaats.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $plaats = Plaats::where("id", $id)->firstOrFail();
            try {
                $plaats->delete();
            } catch (QueryException $queryException) {
                return nietVerwijderdResponse("Plaats", "gewichten/deelnemers");
            }
            return verwijderdResponse("Plaats");
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Plaats");
        }
    }

    /**
     * Ophalen gewichten voor één plaats.
     *
     * @param $id
     * @return JsonResponse
     */
    public function gewichten($id): JsonResponse
    {
        try {
            $plaats = Plaats::where("id", $id)->firstOrFail();
            return response()->json(
                GewichtResource::collection($plaats->gewichten),
                200
            );
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Plaats");
        }
    }

    /**
     * Tonen alle deelnemers van een plaats.
     *
     * @param $id
     * @return JsonResponse
     */
    public function deelnemers($id): JsonResponse
    {
        try {
            $plaats = Plaats::where("id", $id)->firstOrFail();
            return response()->json(
                PlaatsdeelnemerResource::collection($plaats->deelnemers),
                200
            );
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Plaats");
        }
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

    /**
     * @param Request $request
     * @param Plaats $plaats
     * @return array
     */
    private function valideerPlaats(Request $request, Plaats $plaats): array
    {
        return $request->validate(
            [
                "reeks_id" => "bail|required|exists:reeksen,id",
                "nummer" => [
                    "bail",
                    "required",
                    "numeric",
                    "between:1,255",
                    new NummerUniekPerReeks($request['reeks_id'], $plaats->id),
                ],
                "opmerkingen" => "nullable"
            ]
        );
    }
}
