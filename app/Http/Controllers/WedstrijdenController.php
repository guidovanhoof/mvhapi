<?php

namespace App\Http\Controllers;

use App\Http\Resources\WedstrijdResource;
use App\Models\Wedstrijd;
use App\Rules\InKalenderJaar;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use const App\Helpers\STORE_MODE;
use const App\Helpers\UPDATE_MODE;

class WedstrijdenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return WedstrijdResource::collection(Wedstrijd::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validData = $this->valideerWedstrijd($request, new Wedstrijd(), STORE_MODE);

        return $this->wedstrijdResourceResponse(Wedstrijd::create($validData), 201);
    }

    /**
     * Display the specified resource.
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
            return $this->wedstrijdNietGevondenResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $datum
     * @return JsonResponse|Response
     */
    public function update(Request $request, $datum)
    {
        try {
            $wedstrijd = Wedstrijd::where("datum", $datum)->firstOrFail();
            $validData = $this->valideerWedstrijd($request, $wedstrijd, UPDATE_MODE);
            $wedstrijd->update($validData);
            return $this->wedstrijdResourceResponse($wedstrijd, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return $this->wedstrijdNietGevondenResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wedstrijd  $wedstrijd
     * @return Response
     */
    public function destroy(Wedstrijd $wedstrijd)
    {
        //
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
                    new InKalenderJaar($request["kalender_id"])
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
     * @return JsonResponse
     */
    private function wedstrijdNietGevondenResponse(): JsonResponse
    {
        return response()->json(
            ["message" => "Wedstrijd niet gevonden!"],
            404
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
