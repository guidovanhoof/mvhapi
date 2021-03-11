<?php

namespace Tests\Unit\GetrokkenMaat;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetrokkenMaatTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $getrokkenMaat;


    public function setUp(): void
    {
        parent::setUp();

        $this->getrokkenMaat = maakGetrokkenMaat();
    }

    public function tearDown(): void
    {
        cleanUpDb();
        $this->getrokkenMaat = null;

        parent::tearDown();
    }

    /** @test  */
    public function heeftEenWedstrijddeelnemerId()
    {
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();
        $this->getrokkenMaat->wedstrijddeelnemer_id = $wedstrijddeelnemer->id;

        $this->bewaarGetrokkenMaat();

        $this->assertEquals(
            $wedstrijddeelnemer->id, $this->getrokkenMaat->wedstrijddeelnemer_id
        );
    }

    /** @test  */
    public function heeftEenGetrokkenMaatId()
    {
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();
        $this->getrokkenMaat->getrokken_maat_id = $wedstrijddeelnemer->id;

        $this->bewaarGetrokkenMaat();

        $this->assertEquals(
            $wedstrijddeelnemer->id, $this->getrokkenMaat->getrokken_maat_id
        );
    }

    /** @test  */
    public function heeftEenForeignKeyNaarWedstrijddeelnemers()
    {
        $this->expectException(QueryException::class);
        $this->getrokkenMaat->wedstrijddeelnemer_id = 666;

        $this->bewaarGetrokkenMaat();
    }

    /** @test  */
    public function heeftEenForeignKeyNaarWedstrijddeelnemersVoorGetrokkenMaat()
    {
        $this->expectException(QueryException::class);
        $this->getrokkenMaat->getrokken_maat_id = 666;

        $this->bewaarGetrokkenMaat();
    }

    /** @test  */
    public function wedstrijddeelnemerIdGetrokkenMaatIdIsUniek()
    {
        $this->expectException(QueryException::class);

        $getrokkenMaat = bewaarGetrokkenMaat();
        $this->getrokkenMaat->wedstrijddeelnemer_id = $getrokkenMaat->wedstrijddeelnemer_id;
        $this->getrokkenMaat->getrokken_maat_id = $getrokkenMaat->getrokken_maat_id;

        $this->bewaarGetrokkenMaat();
    }

    private function bewaarGetrokkenMaat()
    {
        $this->getrokkenMaat->save();
        $this->getrokkenMaat->fresh();
    }
}
