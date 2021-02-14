<?php

namespace App\Http\Controllers;

use App\Http\Resources\GewichtResource;
use App\Models\Gewicht;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function App\Helpers\nietGevondenResponse;
use function App\Helpers\verwijderdResponse;

class GewichtenController extends Controller
{
    /**
     * Ophalen alle gewichten..
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            GewichtResource::collection(Gewicht::all())
        );
    }

    /**
     * Bewaren nieuw gewicht.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validData = $this->valideerGewicht($request, new Gewicht());

        return $this->gewichtResourceResponse(Gewicht::create($validData), 201);
    }

    /**
     * Ophalen één gewicht.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $gewicht = Gewicht::where("id", $id)->firstOrFail();
            return $this->gewichtResourceResponse($gewicht, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Gewicht");
        }
    }

    /**
     * Wijzig bestaand gewicht.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $gewicht = Gewicht::where("id", $id)->firstOrFail();
            $gewicht->update($this->valideerGewicht($request, $gewicht));
            return $this->gewichtResourceResponse($gewicht, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Gewicht");
        }
    }

    /**
     * Verwijderen bestaand gewicht.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $gewicht = Gewicht::where("id", $id)->firstOrFail();
            $gewicht->delete();
            return verwijderdResponse("Gewicht");
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Gewicht");
        }
    }

    /**
     * @param $gewicht
     * @param int $status
     * @return JsonResponse
     */
    private function gewichtResourceResponse($gewicht, int $status): JsonResponse
    {
        return response()->json(
            new GewichtResource($gewicht),
            $status
        );
    }

    /**
     * @param Request $request
     * @param Gewicht $param
     * @return array
     */
    private function valideerGewicht(Request $request, Gewicht $param): array
    {
        return $request->validate(
            [
                "plaats_id" => "bail|required|exists:plaatsen,id",
                "gewicht" => "bail|required|numeric|gt:0",
                "is_geldig" => "required|boolean"
            ]
        );
    }
}
