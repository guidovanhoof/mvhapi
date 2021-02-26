<?php

namespace Tests\Unit\Rules;

use App\Rules\DeelnemerUniekPerWedstrijd;
use App\Rules\NummerUniekPerWedstrijd;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeelnemerUniekPerWedstrijdTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $wedstrijddeelnemer;

    public function setUp(): void
    {
        parent::setUp();

        $this->wedstrijddeelnemer = bewaarWedstrijddeelnemer();
    }

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test  */
    public function validationFailsForStore()
    {
        $validationRule = new DeelnemerUniekPerWedstrijd($this->wedstrijddeelnemer->wedstrijd_id);

        $this->assertFalse($validationRule->passes("deelnemer_id", $this->wedstrijddeelnemer->deelnemer_id));
        $this->assertEquals("Deelnemer bestaat reeds voor wedstrijd!", $validationRule->message());
    }

    /** @test  */
    public function validationFailsForUpdate()
    {
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();
        $validationRule = new DeelnemerUniekPerWedstrijd($wedstrijddeelnemer->wedstrijd_id, $this->wedstrijddeelnemer->id);

        $this->assertFalse($validationRule->passes("deelnemer_id", $wedstrijddeelnemer->deelnemer_id));
        $this->assertEquals("Deelnemer bestaat reeds voor wedstrijd!", $validationRule->message());
    }

    /** @test  */
    public function validationSucceedsForStore()
    {
        $validationRule = new NummerUniekPerWedstrijd($this->wedstrijddeelnemer->wedstrijd_id);

        $this->assertTrue($validationRule->passes("deelnemer_id", "696969"));
    }

    /** @test  */
    public function validationSucceedsForUpdate()
    {
        $validationRule = new NummerUniekPerWedstrijd($this->wedstrijddeelnemer->wedstrijd_id, $this->wedstrijddeelnemer->id);

        $this->assertTrue($validationRule->passes("deelnemer_id", $this->wedstrijddeelnemer->id));
    }
}
