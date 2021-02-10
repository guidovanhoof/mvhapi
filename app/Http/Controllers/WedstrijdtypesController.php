<?php

namespace App\Http\Controllers;

use App\Http\Resources\WedstrijdtypeResource;
use App\Models\Wedstrijdtype;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use function App\Helpers\nietGevondenResponse;
use function App\Helpers\nietVerwijderdResponse;
use function App\Helpers\verwijderdResponse;
use const App\Helpers\STORING;
use const App\Helpers\UPDATING;

class WedstrijdtypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index()
    {
        return response()->json(
            WedstrijdtypeResource::collection(Wedstrijdtype::all()),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function store(Request $request)
    {
        $validData = $this->valideerWedstrijdtype($request, new Wedstrijdtype(), STORING);

        return $this->wedstrijdtypeResourceResponse(Wedstrijdtype::create($validData), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return WedstrijdtypeResource|JsonResponse
     */
    public function show( $id)
    {
        try {
            $wedstrijdtype = Wedstrijdtype::where("id", $id)->firstOrFail();
            return $this->wedstrijdtypeResourceResponse($wedstrijdtype, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Wedstrijdtype");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse|Response
     */
    public function update(Request $request, $id)
    {
        try {
            $wedstrijdtype = Wedstrijdtype::where("id", $id)->firstOrFail();
            $wedstrijdtype->update(
                $this->valideerWedstrijdtype($request, $wedstrijdtype, UPDATING)
            );
            return $this->wedstrijdtypeResourceResponse($wedstrijdtype, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Wedstrijdtype");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse|Response
     */
    public function destroy($id)
    {
        try {
            $wedstrijdtype = Wedstrijdtype::where("id", $id)->firstOrFail();
            try {
                $wedstrijdtype->delete();
            } catch (QueryException $queryException) {
                return nietVerwijderdResponse("Wedstrijdtype", "wedstrijden");
            }
            return verwijderdResponse("Wedstrijdtype");
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Wedstrijdtype");
        }
    }

    /**
     * @param $wedstrijdtype
     * @param $status
     * @return JsonResponse
     */
    private function wedstrijdtypeResourceResponse($wedstrijdtype, $status): JsonResponse
    {
        return response()->json(
            new WedstrijdtypeResource($wedstrijdtype),
            $status
        );
    }

    /**
     * @param Request $request
     * @param $wedstrijdtype
     * @param $updating
     * @return array
     */
    private function valideerWedstrijdtype(Request $request, Wedstrijdtype $wedstrijdtype, $updating): array
    {
        return $request->validate(
            [
                'omschrijving' => 'required|unique:wedstrijdtypes,omschrijving' . ($updating ? (',' . $wedstrijdtype->id . ',id') : ''),
            ]
        );
    }
}
