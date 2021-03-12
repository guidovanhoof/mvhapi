<?php

namespace Tests\Unit\Rules;

use App\Rules\GetrokkenMaatUniekPerWedstrijddeelnemer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetrokkenMaatUniekPerWedstrijddeelnemerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $getrokkenMaat;

    public function setUp(): void
    {
        parent::setUp();

        $this->getrokkenMaat = bewaarGetrokkenMaat();
    }

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test  */
    public function validationFailsForStore()
    {
        $validationRule = new GetrokkenMaatUniekPerWedstrijddeelnemer(
            $this->getrokkenMaat->wedstrijddeelnemer_id,
        );

        $this->assertFalse(
            $validationRule->passes('getrokken_maat_id', $this->getrokkenMaat->getrokken_maat_id)
        );
        $this->assertEquals("Getrokken_maat_id bestaat reeds voor wedstrijddeelnemer_id!", $validationRule->message());
    }

    /** @test  */
    public function validationFailsForUpdate()
    {
        $getrokkenMaat = bewaarGetrokkenMaat();
        $validationRule = new GetrokkenMaatUniekPerWedstrijddeelnemer(
            $getrokkenMaat->wedstrijddeelnemer_id,
            $this->getrokkenMaat->id,
        );

        $this->assertFalse(
            $validationRule->passes('getrokken_maat_id', $getrokkenMaat->getrokken_maat_id)
        );
        $this->assertEquals(
            'Getrokken_maat_id bestaat reeds voor wedstrijddeelnemer_id!',
            $validationRule->message()
        );
    }

    /** @test  */
    public function validationSucceedsForStore()
    {
        $validationRule = new GetrokkenMaatUniekPerWedstrijddeelnemer(
            $this->getrokkenMaat->wedstrijddeelnemer_id
        );

        $this->assertTrue($validationRule->passes('getrokken_maat_id', "696969"));
    }

    /** @test  */
    public function validationSucceedsForUpdate()
    {
        $validationRule = new GetrokkenMaatUniekPerWedstrijddeelnemer(
            $this->getrokkenMaat->wedstrijddeelnemer_id,
            $this->getrokkenMaat->id,
        );

        $this->assertTrue($validationRule->passes('getrokken_maat_id', $this->getrokkenMaat->id));
    }
}
