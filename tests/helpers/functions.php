<?php

use App\Models\Kalender;
use App\Models\User;
use App\Models\Wedstrijd;
use App\Models\Wedstrijdtype;

const URL_KALENDERS_ADMIN = "api/admin/kalenders/";
const URL_WEDSTRIJDTYPES_ADMIN = "api/admin/wedstrijdtypes/";
const URL_WEDSTRIJDEN_ADMIN = "api/admin/wedstrijden/";

function errorMessage($veld, $response) {
    return $response->json()["errors"][$veld][0];
}

function bewaarKalender($velden = [])
{
    return Kalender::factory()->create($velden);
}

function maakKalender($velden = [])
{
    return Kalender::factory()->make($velden);
}

function bewaarUser($velden = [])
{
    return User::factory()->create($velden);
}

function maakUser($velden = [])
{
    return User::factory()->make($velden);
}

function createUserAndToken(): string
{
    $user = bewaarUser();
    $token = $user->createToken("auth-sanctum");
    return $token->plainTextToken;
}

function bewaarWedstrijdtype($velden = [])
{
    return Wedstrijdtype::factory()->create($velden);
}

function maakWedstrijdtype($velden = [])
{
    return Wedstrijdtype::factory()->make($velden);
}

function bewaarWedstrijd($velden = [])
{
    return Wedstrijd::factory()->create($velden);
}

function maakWedstrijd($velden = [])
{
    return Wedstrijd::factory()->make($velden);
}

