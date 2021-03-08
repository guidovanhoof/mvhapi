<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PlaatsResource;
use App\Http\Resources\Api\ReeksResource;
use App\Models\Reeks;
use App\Rules\NummerUniekPerWedstrijd;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Helpers\nietGevondenResponse;
use function App\Helpers\nietVerwijderdResponse;
use function App\Helpers\verwijderdResponse;

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
     * Bewaar een nieuwe reeks.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validData = $this->valideerReeks($request, new Reeks());

        return $this->reeksResourceResponse(Reeks::create($validData), 201);
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
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $reeks = Reeks::where("id", $id)->firstOrFail();
            $reeks->update(
                $this->valideerReeks($request, $reeks)
            );
            return $this->reeksResourceResponse($reeks, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Reeks");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Reeks $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $reeks = Reeks::where("id", $id)->firstOrFail();
            try {
                $reeks->delete();
            } catch(QueryException $queryException) {
                return nietVerwijderdResponse("Reeks", "plaatsen");
            }
            return verwijderdResponse("Reeks");
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Reeks");
        }
    }

    public function plaatsen($id)
    {
        try {
            $reeks = Reeks::where("id", $id)->firstOrFail();
            return response()->json(
                PlaatsResource::collection($reeks->plaatsen),
                200
            );
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Reeks");
        }
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

    /**
     * @param Request $request
     * @param Reeks $reeks
     * @return array
     */
    private function valideerReeks(Request $request, Reeks $reeks): array
    {
        return $request->validate(
            [
                'wedstrijd_id' => 'bail|required|exists:wedstrijden,id',
                'nummer' => [
                    'bail',
                    'required',
                    'numeric',
                    'between:1,255',
                    new NummerUniekPerWedstrijd($request['wedstrijd_id'], $reeks->id),
                ],
                'aanvang' => 'bail|required|date_format:H:i:s',
                'duur' => 'bail|nullable|date_format:H:i:s',
                'gewicht_zak' => 'bail|required|numeric|gte:0',
                'opmerkingen' => 'bail|nullable',
            ]
        );
    }
}
