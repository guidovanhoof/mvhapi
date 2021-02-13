<?php

use App\Models\Kalender;
use App\Models\Plaats;
use App\Models\Reeks;
use App\Models\User;
use App\Models\Wedstrijd;
use App\Models\Wedstrijdtype;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;

const URL_KALENDERS_ADMIN = "api/admin/kalenders/";
const URL_WEDSTRIJDTYPES_ADMIN = "api/admin/wedstrijdtypes/";
const URL_WEDSTRIJDEN_ADMIN = "api/admin/wedstrijden/";
const URL_REEKSEN_ADMIN = "api/admin/reeksen/";
const URL_PLAATSEN_ADMIN = "api/admin/plaatsen/";

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
 * @param TestCase $testCase
 * @param $data
 * @param Reeks $reeks
 */
function assertReeksEquals(TestCase $testCase, $data, Reeks $reeks): void
{
    $testCase->assertEquals($data["id"], $reeks->id);
    $testCase->assertEquals($data["wedstrijd_id"], $reeks->wedstrijd_id);
    $testCase->assertEquals($data["nummer"], $reeks->nummer);
    $testCase->assertEquals($data["aanvang"], $reeks->aanvang);
    $testCase->assertEquals($data["duur"], $reeks->duur);
    $testCase->assertEquals($data["gewicht_zak"], $reeks->gewicht_zak);
    $testCase->assertEquals($data["opmerkingen"], $reeks->opmerkingen);
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


function bewaarReeks($velden = [])
{
    return Reeks::factory()->create($velden);
}

function maakReeks($velden = [])
{
    return Reeks::factory()->make($velden);
}

/**
 * @param TestCase $testCase
 * @param $data
 * @param Wedstrijdtype $wedstrijdtype
 */
function assertWedstrijdtypeEquals(TestCase $testCase, $data, Wedstrijdtype $wedstrijdtype): void
{
    $testCase->assertEquals($data["id"], $wedstrijdtype->id);
    $testCase->assertEquals($data["omschrijving"], $wedstrijdtype->omschrijving);
}

/**
 * @param Wedstrijd $wedstrijd
 * @return array
 */
function wedstrijdToArray(Wedstrijd $wedstrijd): array
{
    return [
        "kalender_id" => $wedstrijd->kalender_id,
        "nummer" => $wedstrijd->nummer,
        "datum" => $wedstrijd->datum,
        "omschrijving" => $wedstrijd->omschrijving,
        "sponsor" => $wedstrijd->sponsor,
        "aanvang" => $wedstrijd->aanvang,
        "wedstrijdtype_id" => $wedstrijd->wedstrijdtype_id,
        "opmerkingen" => $wedstrijd->opmerkingen,
    ];
}

/**
 * @param Reeks $reeks
 * @return array
 */
function reeksToArray(Reeks $reeks): array
{
    return [
        'wedstrijd_id' => $reeks->wedstrijd_id,
        'nummer' => $reeks->nummer,
        'aanvang' => $reeks->aanvang,
        'duur' => $reeks->duur,
        'gewicht_zak' => $reeks->gewicht_zak,
        'opmerkingen' => $reeks->opmerkingen,
    ];
}

/**
 * @param Plaats $plaats
 * @return array
 */
function plaatsToArray(Plaats $plaats): array
{
    return [
//        'id' => $plaats->id,
        'reeks_id' => $plaats->reeks_id,
        'nummer' => $plaats->nummer,
        'opmerkingen' => $plaats->opmerkingen,
    ];
}

function stdlog($omschrijving, $waarde)
{
    echo "\n### $omschrijving = '$waarde' ###\n";
}


function bewaarPlaats($velden = [])
{
    return Plaats::factory()->create($velden);
}

function maakPlaats($velden = [])
{
    return Plaats::factory()->make($velden);
}


/**
 * @param TestCase $testCase
 * @param $data
 * @param Plaats $plaats
 */
function assertPlaatsEquals(TestCase $testCase, $data, Plaats $plaats): void
{
    $testCase->assertEquals($data["id"], $plaats->id);
    $testCase->assertEquals($data["reeks_id"], $plaats->reeks_id);
    $testCase->assertEquals($data["nummer"], $plaats->nummer);
    $testCase->assertEquals($data["opmerkingen"], $plaats->opmerkingen);
}

/**
 * Maak de nodige tabellen leeg
 *
 * @param string $actie
 */
function cleanUpDb(string $actie)
{
    $verwijderActies = getVerwijderActies();
    foreach ($verwijderActies[$actie] as $verwijderActie) {
        $verwijderActie();
    }
}

/**
 * Lijst met leeg te maken tabellen
 * @return string[][]
 */
function getVerwijderActies(): array
{
    return [
        "plaatsen" => [
            "cleanUpPlaatsen",
            "cleanUpReeksen",
            "cleanUpWedstrijden",
            "cleanUpKalenders",
            "cleanUpWedstrijdtypes",
        ],
        "reeksen" => [
            "cleanUpReeksen",
            "cleanUpWedstrijden",
            "cleanUpKalenders",
            "cleanUpWedstrijdtypes",
        ],
        "wedstrijden" => [
            "cleanUpWedstrijden",
            "cleanUpKalenders",
            "cleanUpWedstrijdtypes",
        ],
        "kalenders" => [
            "cleanUpKalenders",
            "cleanUpWedstrijdtypes",
        ],
    ];
}

function cleanUpPlaatsen()
{
    Plaats::query()->delete();
}

function cleanUpKalenders(): void
{
    Kalender::query()->delete();
}

function cleanUpWedstrijden(): void
{
    Wedstrijd::query()->delete();
}

function cleanUpReeksen(): void
{
    Reeks::query()->delete();
}

function cleanUpWedstrijdtypes(): void
{
    Wedstrijdtype::query()->delete();
}
