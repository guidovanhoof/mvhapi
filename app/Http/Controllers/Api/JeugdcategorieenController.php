<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\JeugdcategorieResource;
use App\Models\Jeugdcategorie;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Helpers\nietGevondenResponse;
use function App\Helpers\verwijderdResponse;

class JeugdcategorieenController extends Controller
{
    /**
     * Tonen alle jeugdcategorieen.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            JeugdcategorieResource::collection(Jeugdcategorie::all())
        );
    }

    /**
     * Bewaren nieuwe jeugdcategorie.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validData = $this->valideerJeugdcategorie($request, new Jeugdcategorie());

        return $this->jeugdcategorieResourceResponse(Jeugdcategorie::create($validData),201);
    }

    /**
     * Tonen één jeugdcategorie.
     *
     * @param  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $jeugdcategorie = Jeugdcategorie::where("id", $id)->firstOrFail();
            return $this->jeugdcategorieResourceResponse($jeugdcategorie, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse('Jeugdcategorie');
        }
    }

    /**
     * Wijzigen bestaande jeugdcategorie.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $jeugdcategorie = Jeugdcategorie::where("id", $id)->firstOrFail();
            $jeugdcategorie->update($this->valideerJeugdcategorie($request, $jeugdcategorie));
            return $this->jeugdcategorieResourceResponse($jeugdcategorie, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse('Jeugdcategorie');
        }
    }

    /**
     * Verwijderen bestaande jeugdcategorie.
     *
     * @param  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $jeugdcategorie = Jeugdcategorie::where("id", $id)->firstOrFail();
            $jeugdcategorie->delete();
            return verwijderdResponse('Jeugdcategorie');
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse('Jeugdcategorie');
        }
    }

    /**
     * @param Jeugdcategorie $jeugdcategorie
     * @param int $status
     * @return JsonResponse
     */
    private function jeugdcategorieResourceResponse(Jeugdcategorie $jeugdcategorie, int $status): JsonResponse
    {
        return response()->json(
            new JeugdcategorieResource($jeugdcategorie),
            $status
        );
    }

    /**
     * @param Request $request
     * @param Jeugdcategorie $jeugdcategorie
     * @return array
     */
    private function valideerJeugdcategorie(Request $request, Jeugdcategorie $jeugdcategorie): array
    {
        return $request->validate(
            [
                'omschrijving' => 'bail|required|unique:jeugdcategorieen,omschrijving' .
                    ($jeugdcategorie->id ? (',' . $jeugdcategorie->id . ',id') : '')
            ]
        );
    }
}
