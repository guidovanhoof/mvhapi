<?php

use App\Models\Kalender;
use App\Models\User;

const URL_KALENDERS_ADMIN = "api/admin/kalenders/";

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

