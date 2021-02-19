<?php

namespace Tests\Unit\Gewicht;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GewichtTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $plaats;
    private $gewicht;

    public function setUp(): void
    {
        parent::setUp();

        $this->plaats = bewaarPlaats();
        $this->gewicht = maakGewicht(["plaats_id" => $this->plaats->id]);
    }

    public function tearDown(): void
    {
        cleanUpDb("gewichten");
        parent::tearDown();
    }

    /** @test */
    public function heeftEenPlaatsId()
    {
        $this->gewicht->plaats_id = $this->plaats->id;

        $this->bewaarGewicht();

        $this->assertDatabaseHas(
            'gewichten',
            gewichtToArry($this->gewicht)
        );
    }

    /** @test */
    public function heeftEenForeignKeyNaarPlaatsen()
    {
        $this->expectException(QueryException::class);
        $this->gewicht->plaats_id = 666;

        $this->bewaarGewicht();
    }

    /** @test */
    public function heeftEenGewicht()
    {
        $this->gewicht->gewicht = 666;

        $this->bewaarGewicht();

        $this->assertDatabaseHas(
            'gewichten',
            gewichtToArry($this->gewicht)
        );
    }

    /** @test */
    public function heeftEenGeldigheid()
    {
        $this->gewicht->is_geldig = 1;

        $this->bewaarGewicht();

        $this->assertDatabaseHas(
            'gewichten',
            gewichtToArry($this->gewicht)
        );
    }

    private function bewaarGewicht(): void
    {
        $this->gewicht->save();
        $this->gewicht->fresh();
    }
}
