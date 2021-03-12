<?php

namespace App\Http\Controllers;

use App\Http\Resources\GetrokkenMaatResource;
use App\Models\GetrokkenMaat;
use App\Rules\GetrokkenMaatUniekPerWedstrijddeelnemer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Helpers\nietGevondenResponse;

class GetrokkenMatenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            GetrokkenMaatResource::collection(GetrokkenMaat::all())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validData = $this->valideerGetrokkenMaat($request, new GetrokkenMaat());

        return $this->getrokkenMaatResourceResponse(
            GetrokkenMaat::create($validData),
            201
        );
    }

    /**
     * Tonen van één getrokken maat.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $getrokkenMaat = GetrokkenMaat::where('id', $id)->firstOrFail();
            return $this->getrokkenMaatResourceResponse($getrokkenMaat, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse('GetrokkenMaat');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param GetrokkenMaat $getrokkenMaat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GetrokkenMaat $getrokkenMaat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param GetrokkenMaat $getrokkenMaat
     * @return \Illuminate\Http\Response
     */
    public function destroy(GetrokkenMaat $getrokkenMaat)
    {
        //
    }

    /**
     * @param GetrokkenMaat $getrokkenMaat
     * @param int $status
     * @return JsonResponse
     */
    private function getrokkenMaatResourceResponse(GetrokkenMaat $getrokkenMaat, int $status): JsonResponse
    {
        return response()->json(
            new GetrokkenMaatResource($getrokkenMaat),
            $status
        );
    }

    /**
     * @param Request $request
     * @param GetrokkenMaat $getrokkenMaat
     * @return array
     */
    private function valideerGetrokkenMaat(Request $request, GetrokkenMaat $getrokkenMaat): array
    {
        return $request->validate(
            [
                'wedstrijddeelnemer_id' => [
                    'bail',
                    'required',
                    'exists:wedstrijddeelnemers,id',
                    'different:getrokken_maat_id',
                ],
                'getrokken_maat_id' => [
                    'bail',
                    'required',
                    'exists:wedstrijddeelnemers,id',
                    'different:wedstrijddeelnemer_id',
                    new GetrokkenMaatUniekPerWedstrijddeelnemer(
                        $request['wedstrijddeelnemer_id'], $getrokkenMaat->id
                    ),
                ],
            ]
        );
    }
}
