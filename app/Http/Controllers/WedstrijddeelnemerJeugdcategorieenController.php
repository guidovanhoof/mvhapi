<?php

namespace App\Http\Controllers;

use App\Http\Resources\WedstrijddeelnemerJeugdcategorieResource;
use App\Models\WedstrijddeelnemerJeugdcategorie;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Helpers\nietGevondenResponse;

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
            WedstrijddeelnemerJeugdcategorieResource::collection(WedstrijddeelnemerJeugdcategorie::all())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
            return $this->resourceResponse($jeugdcategorie);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("WedstrijddeelnemerJeugdcategorie");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie
     * @return \Illuminate\Http\Response
     */
    public function edit(WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie
     * @return \Illuminate\Http\Response
     */
    public function destroy(WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie)
    {
        //
    }

    private function resourceResponse($jeugdcategorie)
    {
        return response()->json(
            new WedstrijddeelnemerJeugdcategorieResource($jeugdcategorie)
        );
    }
}
