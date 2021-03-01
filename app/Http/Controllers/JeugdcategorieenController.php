<?php

namespace App\Http\Controllers;

use App\Http\Resources\JeugdcategorieResource;
use App\Models\Jeugdcategorie;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Helpers\nietGevondenResponse;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Jeugdcategorie  $jeugdcategorie
     * @return \Illuminate\Http\Response
     */
    public function edit(Jeugdcategorie $jeugdcategorie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Jeugdcategorie  $jeugdcategorie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jeugdcategorie $jeugdcategorie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Jeugdcategorie  $jeugdcategorie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jeugdcategorie $jeugdcategorie)
    {
        //
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
}
