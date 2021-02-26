<?php

namespace Tests\Unit\Rules;

use App\Rules\DeelnemerUniekPerPlaats;
use App\Rules\DeelnemerUniekPerWedstrijd;
use App\Rules\NummerUniekPerWedstrijd;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeelnemerUniekPerPlaatsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $plaatsdeelnemer;

    public function setUp(): void
    {
        parent::setUp();

        $this->plaatsdeelnemer = bewaarPlaatsdeelnemer();
    }

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test  */
    public function validationFailsForStore()
    {
        $validationRule = new DeelnemerUniekPerPlaats($this->plaatsdeelnemer->plaats_id);

        $this->assertFalse($validationRule->passes("wedstrijddeelnemer_id", $this->plaatsdeelnemer->wedstrijddeelnemer_id));
        $this->assertEquals("Deelnemer bestaat reeds voor plaats!", $validationRule->message());
    }

    /** @test  */
    public function validationFailsForUpdate()
    {
        $plaatsdeelnemer = bewaarPlaatsdeelnemer();
        $validationRule = new DeelnemerUniekPerPlaats($plaatsdeelnemer->plaats_id, $this->plaatsdeelnemer->plaatsdeelnemer_id);

        $this->assertFalse($validationRule->passes("wedstrijddeelnemer_id", $plaatsdeelnemer->wedstrijddeelnemer_id));
        $this->assertEquals("Deelnemer bestaat reeds voor plaats!", $validationRule->message());
    }

    /** @test  */
    public function validationSucceedsForStore()
    {
        $validationRule = new NummerUniekPerWedstrijd($this->plaatsdeelnemer->wedstrijddeelnemer_id);

        $this->assertTrue($validationRule->passes("wedstrijddeelnemer_id", "696969"));
    }

    /** @test  */
    public function validationSucceedsForUpdate()
    {
        $validationRule = new NummerUniekPerWedstrijd($this->plaatsdeelnemer->wedstrijddeelnemer_id, $this->plaatsdeelnemer->id);

        $this->assertTrue($validationRule->passes("wedstrijddeelnemer_id", $this->plaatsdeelnemer->id));
    }
}
