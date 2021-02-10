<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaatsResource;
use App\Models\Plaats;
use App\Rules\NummerUniekPerReeks;
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
