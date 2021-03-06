<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\KalenderResource;
use App\Http\Resources\Api\WedstrijdResource;
use App\Models\Kalender;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use function App\Helpers\nietGevondenResponse;
use function App\Helpers\nietVerwijderdResponse;
use function App\Helpers\verwijderdResponse;

class KalendersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            KalenderResource::collection(Kalender::all()->sortByDesc("jaar")),
            200
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
        $validData = $this->valideerKalender($request, new Kalender());

        return $this->kalenderResourceResponse(Kalender::create($validData), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $jaar
     * @return KalenderResource|JsonResponse
     */
    public function show($jaar)
    {
        try {
            $kalender = Kalender::where("jaar", $jaar)->firstOrFail();
            return $this->kalenderResourceResponse($kalender, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Kalender");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $jaar
     * @return JsonResponse
     */
    public function update(Request $request, $jaar): JsonResponse
    {
        try {
            $kalender = Kalender::where("jaar", $jaar)->firstOrFail();
            $kalender->update(
                $this->valideerKalender($request, $kalender)
            );
            return $this->kalenderResourceResponse($kalender, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Kalender");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $jaar
     * @return JsonResponse
     */
    public function destroy($jaar): JsonResponse
    {
        try {
            $kalender = Kalender::where("jaar", $jaar)->firstOrFail();
            try {
                $kalender->delete();
            } catch(QueryException $queryException) {
                return nietVerwijderdResponse("Kalender", "wedstrijden");
            }
            return verwijderdResponse("Kalender");
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Kalender");
        }
    }

    /**
     * Overzicht alle westrijden van een kalender
     *
     * @param $jaar
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function wedstrijden($jaar)
    {
        try {
            $kalender = Kalender::where("jaar", $jaar)->firstOrFail();
            return response()->json(
                WedstrijdResource::collection($kalender->wedstrijden)
                ,200
            );
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Kalender");
        }
    }

    /**
     * @param Kalender $kalender
     * @param int $status
     * @return JsonResponse
     */
    private function kalenderResourceResponse(Kalender $kalender, int $status): JsonResponse
    {
        return response()->json(
            new KalenderResource($kalender),
            $status
        );
    }

    /**
     * @param Request $request
     * @param Kalender $kalender
     * @return array
     */
    private function valideerKalender(Request $request, Kalender $kalender): array
    {
        return $request->validate(
            [
                'jaar' => 'required|unique:kalenders,jaar' . ($kalender->id ? (',' . $kalender->id . ',id'): ''),
                'opmerkingen' => 'nullable'
            ]
        );
    }
}
