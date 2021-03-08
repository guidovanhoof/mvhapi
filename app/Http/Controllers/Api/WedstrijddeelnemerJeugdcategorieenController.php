<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\WedstrijddeelnemerJeugdcategorieResource;
use App\Models\WedstrijddeelnemerJeugdcategorie;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Helpers\nietGevondenResponse;
use function App\Helpers\verwijderdResponse;

class WedstrijddeelnemerJeugdcategorieenController extends Controller
{
    /**
     * Tonen alle wedstrijddeelnemers met een jeugdcategorie.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            WedstrijddeelnemerJeugdcategorieResource::collection(
                WedstrijddeelnemerJeugdcategorie::all()
            )
        );
    }

    /**
     * Bewaren nieuwe wedstrijddeelnemer jeugdcategorie.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validData = $this->valideerWedstrijddeelnemerJeugdcategorie($request, new WedstrijddeelnemerJeugdcategorie());
        return $this->resourceResponse(
            WedstrijddeelnemerJeugdcategorie::create($validData),
            201
        );
    }

    /**
     * Tonen één wedstrijddeelnemer met jeugdcategorie.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $jeugdcategorie = WedstrijddeelnemerJeugdcategorie::where("id", $id)->firstOrFail();
            return $this->resourceResponse($jeugdcategorie, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("WedstrijddeelnemerJeugdcategorie");
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
            $wedstrijddeelnemerJeugdcategorie = WedstrijddeelnemerJeugdcategorie::where("id", $id)->firstOrFail();
            $wedstrijddeelnemerJeugdcategorie->update(
                $this->valideerWedstrijddeelnemerJeugdcategorie($request, $wedstrijddeelnemerJeugdcategorie)
            );
            return $this->resourceResponse(
                $wedstrijddeelnemerJeugdcategorie,
                200
            );
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("WedstrijddeelnemerJeugdcategorie");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $wedstrijddeelnemerJeugdcategorie = WedstrijddeelnemerJeugdcategorie::where("id", $id)->firstOrFail();
            $wedstrijddeelnemerJeugdcategorie->delete();
            return verwijderdResponse("WedstrijddeelnemerJeugdcategorie");
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("WedstrijddeelnemerJeugdcategorie");
        }
    }

    /**
     * @param $jeugdcategorie
     * @param int $status
     * @return JsonResponse
     */
    private function resourceResponse($jeugdcategorie, int $status): JsonResponse
    {
        return response()->json(
            new WedstrijddeelnemerJeugdcategorieResource($jeugdcategorie),
            $status
        );
    }

    /**
     * @param Request $request
     * @param WedstrijddeelnemerJeugdcategorie $wdeelnemerJcategorie
     * @return array
     */
    private function valideerWedstrijddeelnemerJeugdcategorie(
        Request $request, WedstrijddeelnemerJeugdcategorie $wdeelnemerJcategorie
    ): array
    {
        return $request->validate(
            [
                'wedstrijddeelnemer_id' =>
                    'bail|required|exists:wedstrijddeelnemers,id|' .
                    'unique:wdeelnemers_jcategorieen,wedstrijddeelnemer_id' .
                    ($wdeelnemerJcategorie->id ? (',' . $wdeelnemerJcategorie->id . ',id') : ''),
                'jeugdcategorie_id' =>
                    'bail|required|exists:jeugdcategorieen,id',
            ]
        );
    }
}
