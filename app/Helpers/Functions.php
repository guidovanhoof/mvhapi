<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

/**
 * @param $tekst
 * @return string|string[]
 */
function verwijderAccenten($tekst)
{
    return
        str_replace(
            [
                "á", "é", "ý", "ú", "í", "ó",
                "à", "è", "ù", "ì", "ò",
                "â", "ê", "û", "î", "ô",
                "^", "´", "`",
                "¨", "ä", "ë", "ÿ", "ü", "ï", "ö",
                "~", "ã", "õ", "ñ",
                "ç",
            ],
            [
                "a", "e", "y", "u", "i", "o",
                "a", "e", "u", "i", "o",
                "a", "e", "u", "i", "o",
                "", "", "",
                "", "a", "e", "y", "u", "i", "o",
                "", "a", "o", "n",
                "c",
            ],
            $tekst
        );
}

/**
 * @param $omschrijving
 * @return JsonResponse
 */
function verwijderdResponse($omschrijving): JsonResponse
{
    return response()->json(
        ["message" => "$omschrijving verwijderd!"],
        200
    );
}

/**
 * @param $omschrijving
 * @return JsonResponse
 */
function nietGevondenResponse($omschrijving): JsonResponse
{
    return response()->json(
        ["message" => "$omschrijving niet gevonden!"],
        404
    );
}

/**
 * @param $omschrijving
 * @param $reden
 * @return JsonResponse
 */
function nietVerwijderdResponse($omschrijving, $reden): JsonResponse
{
    return response()->json(
        ["message" => "$omschrijving niet verwijderd! Nog $reden aanwezig!"],
        405
    );
}
