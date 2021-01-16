<?php

use App\Models\Kalender;

const ADMIN_API_URL = "api/admin/kalenders";

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
