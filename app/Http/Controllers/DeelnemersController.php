<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeelnemerResource;
use App\Models\Deelnemer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Helpers\nietGevondenResponse;

class DeelnemersController extends Controller
{
    /**
     * Ophalen alle deelnemers.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            DeelnemerResource::collection(Deelnemer::all()),
            200
        );
    }

    /**
     * Bewaren nieuwe deelnemer.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validData = $this->valideerDeelnemer($request, new Deelnemer());

        $deelnemer = Deelnemer::create($validData);
        return $this->deelnemerResourceResponse($deelnemer, 201);
    }

    /**
     * Ophalen één deelnemer.
     *
     * @param  $nummer
     * @return JsonResponse
     */
    public function show($nummer): JsonResponse
    {
        try {
            $deelnemer = Deelnemer::where("nummer", $nummer)->firstOrFail();
            return $this->deelnemerResourceResponse($deelnemer, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Deelnemer");
        }
    }

    /**
     * Bewaren bestaande deelnemer.
     *
     * @param Request $request
     * @param  $nummer
     * @return JsonResponse
     */
    public function update(Request $request, $nummer): JsonResponse
    {
        try {
            $deelnemer = Deelnemer::where("nummer", $nummer)->firstOrFail();
            $deelnemer->update($this->valideerDeelnemer($request, $deelnemer));
            return $this->deelnemerResourceResponse($deelnemer, 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return nietGevondenResponse("Deelenemer");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deelnemer  $deelnemer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deelnemer $deelnemer)
    {
        //
    }

    /**
     * @param Deelnemer $deelnemer
     * @param int $status
     * @return JsonResponse
     */
    private function deelnemerResourceResponse(Deelnemer $deelnemer, int $status): JsonResponse
    {
        return response()->json(
            new DeelnemerResource($deelnemer),
            $status
        );
    }

    /**
     * @param Request $request
     * @param Deelnemer $deelnemer
     * @return array
     */
    private function valideerDeelnemer(Request $request, Deelnemer $deelnemer): array
    {
        return $request->validate(
            [
                "nummer" => "bail|required|numeric|gt:0|unique:deelnemers,nummer" .
                    ($deelnemer->id ? (',' . $deelnemer->id . ',id') : ''),
                "naam" => "bail|required|unique:deelnemers,naam" .
                    ($deelnemer->naam ? (',' . $deelnemer->naam . ',naam') : '')
            ]
        );
    }
}
