<?php

use App\Models\Kalender;
use App\Models\User;
use App\Models\Wedstrijd;
use App\Models\Wedstrijdtype;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

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

/**
 * @param TestCase $tester
 * @param $data
 * @param Kalender $kalender
 */
function assertKalenderEquals(TestCase $tester, $data, Kalender $kalender): void
{
    $tester->assertEquals($data["jaar"], $kalender->jaar);
    $tester->assertEquals($data["omschrijving"], $kalender->omschrijving());
    $tester->assertEquals($data["opmerkingen"], $kalender->opmerkingen);
}

/**
 * @param TestCase $tester
 * @param $data
 * @param Wedstrijd $wedstrijd
 */
function assertWedstrijdEquals(TestCase $tester, $data, Wedstrijd $wedstrijd): void
{
    $tester->assertEquals($data["kalender_id"], $wedstrijd->kalender_id);
    $tester->assertEquals($data["datum"], $wedstrijd->datum);
    $tester->assertEquals($data["nummer"], $wedstrijd->nummer);
    $tester->assertEquals($data["omschrijving"], $wedstrijd->omschrijving);
    $tester->assertEquals($data["sponsor"], $wedstrijd->sponsor);
    $tester->assertEquals($data["aanvang"], $wedstrijd->aanvang);
    $tester->assertEquals($data["wedstrijdtype_id"], $wedstrijd->wedstrijdtype_id);
    $tester->assertEquals($data["opmerkingen"], $wedstrijd->opmerkingen);
}

/**
 * @param TestCase $testcase
 * @param string $veld
 * @param TestResponse $response
 * @param string $expectedErrorMessage
 */
function assertErrorMessage(
    TestCase $testcase, string $veld, TestResponse $response, string $expectedErrorMessage
): void
{
    $response->assertStatus(422);
    $testcase->assertEquals(errorMessage($veld, $response), $expectedErrorMessage);
}
