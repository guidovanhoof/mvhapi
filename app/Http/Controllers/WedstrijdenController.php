<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReeksResource;
use App\Http\Resources\WedstrijdResource;
use App\Models\Wedstrijd;
use App\Rules\DatumInKalenderJaar;
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

class WedstrijdenController extends Controller
{
    /**
     * Tonen van alle wedstrijden.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            WedstrijdResource::collection(Wedstrijd::all()->sortByDesc("datum")),
            200
        );
    }

    /**
     * Bewaren van een nieuwe wedstrijd.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validData = $this->valideerWedstrijd($request, new Wedstrijd(), STORING);

        return $this->wedstrijdResourceResponse(Wedstrijd::create($validData), 201);
    }

    /**
     * Tonen van een specifieke wedstrijd.
     *
     * @param $datum
     * @return WedstrijdResource|JsonResponse
     */
    public function show($datum)
    {
        try {
            $wedstrijd = Wedstrijd::where("datum", $datum)->firstOrFail();
            return $this->wedstrijdResourceResponse($wedstrijd, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Wedstrijd");
        }
    }

    /**
     * Wijzigen van een specifieke wedstrijd.
     *
     * @param Request $request
     * @param $datum
     * @return JsonResponse|Response
     */
    public function update(Request $request, $datum)
    {
        try {
            $wedstrijd = Wedstrijd::where("datum", $datum)->firstOrFail();
            $wedstrijd->update(
                $this->valideerWedstrijd($request, $wedstrijd, UPDATING)
            );
            return $this->wedstrijdResourceResponse($wedstrijd, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Wedstrijd");
        }
    }

    /**
     * Verwijderen van een specifieke wedstrijd.
     *
     * @param $datum
     * @return JsonResponse|Response
     */
    public function destroy($datum)
    {
        try {
            $wedstrijd = Wedstrijd::where("datum", $datum)->firstOrFail();
            try {
                $wedstrijd->delete();
            } catch (QueryException $queryException) {
                return nietVerwijderdResponse("Wedstrijd", "reeksen");
            }
            return verwijderdResponse("Wedstrijd");
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Wedstrijd");
        }
    }

    /**
     * Tonen alle reeksen van een wedstrijd
     *
     * @param $datum
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function reeksen($datum)
    {
        try {
            $wedstrijd = Wedstrijd::where("datum", $datum)->firstOrFail();
            return response()->json(
                ReeksResource::collection($wedstrijd->reeksen),
                200
            );
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Wedstrijd");
        }
    }

    /**
     * @param Request $request
     * @param $wedstrijd
     * @param $update
     * @return array
     */
    private function valideerWedstrijd(Request $request, $wedstrijd, $update): array
    {
        return $request->validate(
            [
                'kalender_id' => 'required|exists:kalenders,id',
                'datum' => [
                    'bail',
                    'required',
                    'date',
                    'unique:wedstrijden,datum' . ($update ? ',' . $wedstrijd->datum . ',datum' : ''),
                    new DatumInKalenderJaar($request["kalender_id"])
                ],
                'nummer' => 'nullable|numeric|between:1,65535',
                'omschrijving' => 'required',
                'sponsor' => 'nullable',
                'aanvang' => 'required|date_format:H:i:s',
                'wedstrijdtype_id' => 'required|exists:wedstrijdtypes,id',
                'opmerkingen' => 'nullable',
            ]
        );
    }

    /**
     * @param $wedstrijd
     * @param $status
     * @return JsonResponse
     */
    private function wedstrijdResourceResponse($wedstrijd, $status): JsonResponse
    {
        return response()->json(
            new WedstrijdResource($wedstrijd),
            $status
        );
    }
}
