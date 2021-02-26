<?php

namespace Tests\Unit\Plaats;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlaatsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $reeks;
    private $plaats;

    public function setUp(): void
    {
        parent::setUp();

        $this->reeks = bewaarReeks();
        $this->plaats = maakPlaats(["reeks_id" => $this->reeks->id]);
    }

    public function tearDown(): void
    {
        cleanUpDb();
        $this->reeks = null;
        $this->plaats = null;

        parent::tearDown();
    }

    /** @test  */
    public function heeftEenReeksId()
    {
        $this->plaats->reeks_id = $this->reeks->id;

        $this->bewaarPlaats();

        $this->assertEquals($this->plaats->reeks_id, $this->reeks->id);
    }

    /** @test  */
    public function heeftEenForeignKeyNaarReeks()
    {
        $this->expectException(QueryException::class);

        $this->plaats->reeks_id = 666;

        $this->bewaarPlaats();
    }

    /** @test  */
    public function heeftEenNummer()
    {
        $expectedNummer = 66;
        $this->plaats->nummer = $expectedNummer;

        $this->bewaarPlaats();

        $this->assertEquals($expectedNummer, $this->plaats->nummer);
    }

    /** @test  */
    public function heeftOpmerkingen()
    {
        $expectedOpmerkingen = 'opmerkingen';
        $this->plaats->opmerkingen = $expectedOpmerkingen;

        $this->bewaarPlaats();

        $this->assertEquals($expectedOpmerkingen, $this->plaats->opmerkingen);
    }

    /** @test  */
    public function opmerkingenIsOptioneel()
    {
        $expectedOpmerkingen = null;
        $this->plaats->opmerkingen = $expectedOpmerkingen;

        $this->bewaarPlaats();

        $this->assertEquals($expectedOpmerkingen, $this->plaats->opmerkingen);
    }

    /** @test  */
    public function nummerIsUniekPerReeks()
    {
        $this->expectException(QueryException::class);

        $this->bewaarPlaats();
        bewaarPlaats(
            [
                "reeks_id" => $this->plaats->reeks_id,
                "nummer" => $this->plaats->nummer,
            ]
        );
    }

    /** @test  */
    public function heeftGewichten()
    {
        $this->bewaarPlaats();
        $expectGewicht = bewaarGewicht(["plaats_id" => $this->plaats->id]);

        $actualGewichten = $this->plaats->gewichten;

        $this->assertCount(1, $actualGewichten);
        assertGewichtEquals($this, $actualGewichten[0], $expectGewicht);
    }

    /** @test  */
    public function heeftDeelnemers()
    {
        $this->bewaarPlaats();
        $deelnemer = bewaarWedstrijddeelnemer();
        $expectDeelnemer = bewaarPlaatsdeelnemer(
            ["plaats_id" => $this->plaats->id, "wedstrijddeelnemer_id" => $deelnemer->id]
        );

        $actualDeelnemers = $this->plaats->deelnemers;

        $this->assertCount(1, $actualDeelnemers);
        assertPlaatsdeelnemerEquals($this, $actualDeelnemers[0], $expectDeelnemer);
    }

    private function bewaarPlaats(): void
    {
        $this->plaats->save();
        $this->plaats->fresh();
    }
}
