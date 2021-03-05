<?php

use App\Models\Deelnemer;
use App\Models\Gewicht;
use App\Models\Jeugdcategorie;
use App\Models\Kalender;
use App\Models\Plaats;
use App\Models\Plaatsdeelnemer;
use App\Models\Reeks;
use App\Models\User;
use App\Models\Wedstrijd;
use App\Models\Wedstrijddeelnemer;
use App\Models\WedstrijddeelnemerJeugdcategorie;
use App\Models\Wedstrijdtype;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

const URL_KALENDERS_ADMIN = "api/admin/kalenders/";
const URL_WEDSTRIJDTYPES_ADMIN = "api/admin/wedstrijdtypes/";
const URL_WEDSTRIJDEN_ADMIN = "api/admin/wedstrijden/";
const URL_REEKSEN_ADMIN = "api/admin/reeksen/";
const URL_PLAATSEN_ADMIN = "api/admin/plaatsen/";
const URL_GEWICHTEN_ADMIN = "api/admin/gewichten/";
const URL_DEELNEMERS_ADMIN = "api/admin/deelnemers/";
const URL_WEDSTRIJDDEELNEMERS_ADMIN = "api/admin/wedstrijddeelnemers/";
const URL_PLAATSDEELNEMERS_ADMIN = "api/admin/plaatsdeelnemers/";
const URL_JEUGDCATEGORIEEN_ADMIN = "api/admin/jeugdcategorieen/";
const URL_WEDSTRIJDDEELNEMERJEUGDCATEGORIEEN_ADMIN = "api/admin/wedstrijddeelnemerjeugdcategorieen/";

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
function assertErrorMessage(TestCase $testcase, string $veld, TestResponse $response, string $expectedErrorMessage): void
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
 */
function cleanUpDb()
{
    Gewicht::query()->delete();
    Plaatsdeelnemer::query()->delete();
    Plaats::query()->delete();
    Reeks::query()->delete();
    WedstrijddeelnemerJeugdcategorie::query()->delete();
    Wedstrijddeelnemer::query()->delete();
    Wedstrijd::query()->delete();
    Jeugdcategorie::query()->delete();
    Wedstrijdtype::query()->delete();
    Deelnemer::query()->delete();
    Kalender::query()->delete();
}

function bewaarGewicht($velden = [])
{
    return Gewicht::factory()->create($velden);
}

function maakGewicht($velden = [])
{
    return Gewicht::factory()->make($velden);
}

/**
 * @param TestCase $testCase
 * @param $data
 * @param Gewicht $gewicht
 */
function assertGewichtEquals(TestCase $testCase, $data, Gewicht $gewicht): void
{
    $testCase->assertEquals($data["id"], $gewicht->id);
    $testCase->assertEquals($data["plaats_id"], $gewicht->plaats_id);
    $testCase->assertEquals($data["gewicht"], $gewicht->gewicht);
    $testCase->assertEquals($data["is_geldig"], $gewicht->is_geldig);
}

/**
 * @param Gewicht $gewicht
 * @return array
 */
function gewichtToArry(Gewicht $gewicht): array
{
    return [
        'plaats_id' => $gewicht->plaats_id,
        'gewicht' => $gewicht->gewicht,
        'is_geldig' => $gewicht->is_geldig,
    ];
}

function bewaarDeelnemer($velden = [])
{
    return Deelnemer::factory()->create($velden);
}

function maakDeelnemer($velden = [])
{
    return Deelnemer::factory()->make($velden);
}

/**
 * @param TestCase $testCase
 * @param $data
 * @param Deelnemer $deelnemer
 */
function assertDeelnemerEquals(TestCase $testCase, $data, Deelnemer $deelnemer): void
{
    $testCase->assertEquals($data["nummer"], $deelnemer->nummer);
    $testCase->assertEquals($data["naam"], $deelnemer->naam);
}

/**
 * @param Deelnemer $deelnemer
 * @return array
 */
function deelnemerToArry(Deelnemer $deelnemer): array
{
    return [
        'nummer' => $deelnemer->nummer,
        'naam' => $deelnemer->naam,
    ];
}

function bewaarWedstrijddeelnemer($velden = [])
{
    return Wedstrijddeelnemer::factory()->create($velden);
}

function maakWedstrijddeelnemer($velden = [])
{
    return Wedstrijddeelnemer::factory()->make($velden);
}

/**
 * @param TestCase $testCase
 * @param $data
 * @param Wedstrijddeelnemer $wedstrijddeelnemer
 */
function assertWedstrijddeelnemerEquals(TestCase $testCase, $data, Wedstrijddeelnemer $wedstrijddeelnemer): void
{
    $testCase->assertEquals($data["wedstrijd_id"], $wedstrijddeelnemer->wedstrijd_id);
    $testCase->assertEquals($data["deelnemer_id"], $wedstrijddeelnemer->deelnemer_id);
    $testCase->assertEquals($data["is_gediskwalificeerd"], $wedstrijddeelnemer->is_gediskwalificeerd);
    $testCase->assertEquals($data["opmerkingen"], $wedstrijddeelnemer->opmerkingen);
}

/**
 * @param Wedstrijddeelnemer $wedstrijddeelnemer
 * @return array
 */
function wedstrijddeelnemerToArry(Wedstrijddeelnemer $wedstrijddeelnemer): array
{
    return [
        'wedstrijd_id' => $wedstrijddeelnemer->wedstrijd_id,
        'deelnemer_id' => $wedstrijddeelnemer->deelnemer_id,
        'is_gediskwalificeerd' => $wedstrijddeelnemer->is_gediskwalificeerd,
        'opmerkingen' => $wedstrijddeelnemer->opmerkingen,
    ];
}

/**
 * @param TestCase $testCase
 * @param Wedstrijddeelnemer $wedstrijddeelnemer
 */
function assertWedstrijddeelnemerInDatabase(TestCase $testCase, Wedstrijddeelnemer $wedstrijddeelnemer): void
{
    $testCase->assertEquals(
        1,
        Wedstrijddeelnemer::where(wedstrijddeelnemerToArry($wedstrijddeelnemer))->count()
    );
    $testCase->assertJson($wedstrijddeelnemer->toJson())
    ;
}

/**
 * @param TestCase $testCase
 * @param Deelnemer $deelnemer
 */
function assertDeelnemerInDatabase(TestCase $testCase, Deelnemer $deelnemer): void
{
    $testCase->assertEquals(
        1,
        Deelnemer::where(deelnemerToArry($deelnemer))->count()
    );
    $testCase->assertJson($deelnemer->toJson())
    ;
}

/**
 * @param TestCase $testCase
 * @param Gewicht $gewicht
 */
function assertGewichtInDatabase(TestCase $testCase, Gewicht $gewicht): void
{
    $testCase->assertEquals(
        1,
        Gewicht::where(gewichtToArry($gewicht))->count()
    );
    $testCase->assertJson($gewicht->toJson())
    ;
}

/**
 * @param Kalender $kalender
 * @return array
 */
function kalenderToArray(Kalender $kalender): array
{
    return [
        "jaar" => $kalender->jaar,
        "opmerkingen" => $kalender->opmerkingen
    ];
}

/**
 * @param TestCase $testCase
 * @param Kalender $kalender
 */
function assertKalenderInDatabase(TestCase $testCase, Kalender $kalender): void
{
    $testCase->assertEquals(
        1,
        Kalender::where(kalenderToArray($kalender))->count()
    );
    $testCase->assertJson($kalender->toJson())
    ;
}

/**
 * @param TestCase $testCase
 * @param Plaats $plaats
 */
function assertPlaatsInDatabase(TestCase $testCase, Plaats $plaats): void
{
    $testCase->assertEquals(
        1,
        Plaats::where(plaatsToArray($plaats))->count()
    );
    $testCase->assertJson($plaats->toJson())
    ;
}

/**
 * @param TestCase $testCase
 * @param Reeks $reeks
 */
function assertReeksInDatabase(TestCase $testCase, Reeks $reeks): void
{
    $testCase->assertEquals(
        1,
        Reeks::where(reeksToArray($reeks))->count()
    );
    $testCase->assertJson($reeks->toJson())
    ;
}

/**
 * @param TestCase $testCase
 * @param Wedstrijd $wedstrijd
 */
function assertWedstrijdInDatabase(TestCase $testCase, Wedstrijd $wedstrijd): void
{
    $testCase->assertEquals(
        1,
        Wedstrijd::where(wedstrijdToArray($wedstrijd))->count()
    );
    $testCase->assertJson($wedstrijd->toJson())
    ;
}

/**
 * @param TestCase $testCase
 * @param Wedstrijdtype $wedstrijdtype
 */
function assertWedstrijdtypeInDatabase(TestCase $testCase, Wedstrijdtype $wedstrijdtype): void
{
    $testCase->assertEquals(
        1,
        Wedstrijdtype::where(wedstrijdtypeToArray($wedstrijdtype))->count()
    );
    $testCase->assertJson($wedstrijdtype->toJson())
    ;
}

function assertNietGevonden(TestCase $testCase, TestResponse $response, $entiteit)
{
    $response->assertStatus(404);
    $errorMessage = $response->json()["message"];
    $testCase->assertEquals("$entiteit niet gevonden!", $errorMessage);
}

/**
 * @param Wedstrijdtype $wedstrijdtype
 * @return array
 */
function wedstrijdtypeToArray(Wedstrijdtype $wedstrijdtype): array
{
    return ["id" => $wedstrijdtype->id, "omschrijving" => $wedstrijdtype->omschrijving];
}

/**
 * @param TestCase $testCase
 * @param TestResponse $response
 * @param string $table
 * @param Model $model
 */
function assertDataseMissing(TestCase $testCase, TestResponse $response, string $table, Model $model)
{
    $response->assertStatus(200);
    $errorMessage = $response->json()["message"];
    $testCase->assertEquals(
            0,
            get_class($model)::where($model)->count()
    );
    $testCase->assertEquals("Deelnemer verwijderd!", $errorMessage);
}


function bewaarPlaatsdeelnemer($velden = [])
{
    return Plaatsdeelnemer::factory()->create($velden);
}

function maakPlaatsdeelnemer($velden = [])
{
    return Plaatsdeelnemer::factory()->make($velden);
}

/**
 * @param TestCase $testCase
 * @param $data
 * @param Plaatsdeelnemer $plaatsdeelnemer
 */
function assertPlaatsdeelnemerEquals(TestCase $testCase, $data, Plaatsdeelnemer $plaatsdeelnemer): void
{
    $testCase->assertEquals($data["plaats_id"], $plaatsdeelnemer->plaats_id);
    $testCase->assertEquals($data["wedstrijddeelnemer_id"], $plaatsdeelnemer->wedstrijddeelnemer_id);
    $testCase->assertEquals($data["is_weger"], $plaatsdeelnemer->is_weger);
}

/**
 * @param TestCase $testCase
 * @param Plaatsdeelnemer $plaatsdeelnemer
 */
function assertPlaatsdeelnemerInDatabase(TestCase $testCase, Plaatsdeelnemer $plaatsdeelnemer): void
{
    $testCase->assertEquals(
        1,
        Plaatsdeelnemer::where(plaatsdeelnemerToArry($plaatsdeelnemer))->count()
    );
    $testCase->assertJson($plaatsdeelnemer->toJson())
    ;
}

/**
 * @param Plaatsdeelnemer $plaatsdeelnemer
 * @return array
 */
function plaatsdeelnemerToArry(Plaatsdeelnemer $plaatsdeelnemer): array
{
    return [
        'plaats_id' => $plaatsdeelnemer->plaats_id,
        'wedstrijddeelnemer_id' => $plaatsdeelnemer->wedstrijddeelnemer_id,
        'is_weger' => $plaatsdeelnemer->is_weger,
    ];
}

function bewaarJeugdcategorie($velden = [])
{
    return Jeugdcategorie::factory()->create($velden);
}

function maakJeugdcategorie($velden = [])
{
    return Jeugdcategorie::factory()->make($velden);
}

/**
 * @param TestCase $testCase
 * @param $data
 * @param Jeugdcategorie $jeugdcategorie
 */
function assertJeugdcategorieEquals(TestCase $testCase, $data, Jeugdcategorie $jeugdcategorie): void
{
    $testCase->assertEquals($data["omschrijving"], $jeugdcategorie->omschrijving);
}

/**
 * @param TestCase $testCase
 * @param Jeugdcategorie $jeugdcategorie
 */
function assertJeugdcategorieInDatabase(TestCase $testCase, Jeugdcategorie $jeugdcategorie): void
{
    $testCase->assertEquals(
        1,
        Jeugdcategorie::where(jeugdcategorieToArray($jeugdcategorie))->count()
    );
    $testCase->assertJson($jeugdcategorie->toJson())
    ;
}

/**
 * @param Jeugdcategorie $jeugdcategorie
 * @return array
 */
function jeugdcategorieToArray(Jeugdcategorie $jeugdcategorie): array
{
    return [
        'omschrijving' => $jeugdcategorie->omschrijving,
    ];
}

function bewaarWedstrijddeelnemerJeugdcategorie($velden = [])
{
    return WedstrijddeelnemerJeugdcategorie::factory()->create($velden);
}

function maakWedstrijddeelnemerJeugdcategorie($velden = [])
{
    return WedstrijddeelnemerJeugdcategorie::factory()->make($velden);
}

/**
 * @param TestCase $testCase
 * @param $data
 * @param WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie
 */
function assertWedstrijddeelnemerJeugdcategorieEquals(
    TestCase $testCase, $data, WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie
): void
{
    $testCase->assertEquals($data["wedstrijddeelnemer_id"], $wedstrijddeelnemerJeugdcategorie->wedstrijddeelnemer_id);
    $testCase->assertEquals($data["jeugdcategorie_id"], $wedstrijddeelnemerJeugdcategorie->jeugdcategorie_id);
}

/**
 * @param TestCase $testCase
 * @param WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie
 */
function assertWedstrijddeelnemerJeugdcategorieInDatabase(TestCase $testCase, WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie): void
{
    $testCase->assertEquals(
        1,
        WedstrijddeelnemerJeugdcategorie::where(jeugdcategorieToArray($wedstrijddeelnemerJeugdcategorie))->count()
    );
    $testCase->assertJson($wedstrijddeelnemerJeugdcategorie->toJson())
    ;
}

/**
 * @param WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie
 * @return array
 */
function wedstrijddeelnemerJeugdcategorieToArrayw(WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie): array
{
    return [
        'wedstrijddeelnemer_id' => $wedstrijddeelnemerJeugdcategorie->wedstrijddeelnemer_id,
        'jeugdcategorie_id' => $wedstrijddeelnemerJeugdcategorie->jeugdcategorie_id,
    ];
}

/**
 * @param TestResponse $response
 * @return mixed
 */
function getJsonFromResponse(TestResponse $response)
{
    return $response->json();
}
