<?php

namespace Tests\Unit\Rules;

use App\Models\Kalender;
use App\Models\Reeks;
use App\Models\Wedstrijd;
use App\Rules\NummerUniekPerWedstrijd;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NummerUniekPerWedstrijdTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private $reeks;

    public function setUp(): void
    {
        parent::setUp();

        $this->reeks = bewaarReeks(["nummer" => 66]);
    }

    public function tearDown(): void
    {
        cleanUpDb("reeksen");

        parent::tearDown();
    }


    /** @test  */
    public function validationFails()
    {
        $validationRule = new NummerUniekPerWedstrijd($this->reeks->wedstrijd_id);

        $this->assertFalse($validationRule->passes("nummer", 66));
        $this->assertEquals($validationRule->message(), "Nummer bestaat reeds voor wedstrijd!");
    }

    /** @test  */
    public function validationSucceeds()
    {
        $validationRule = new NummerUniekPerWedstrijd($this->reeks->wedstrijd_id);

        $this->assertTrue($validationRule->passes("nummer", "67"));
    }
}
