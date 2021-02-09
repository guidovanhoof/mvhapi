<?php

namespace Tests\Unit\Rules;

use App\Rules\NummerUniekPerReeks;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NummerUniekPerReeksTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $plaats;

    public function setUp(): void
    {
        parent::setUp();

        $this->plaats = bewaarPlaats(["nummer" => 66]);
    }

    public function tearDown(): void
    {
        cleanUpDb("plaatsen");

        parent::tearDown();
    }


    /** @test  */
    public function validationFails()
    {
        $validationRule = new NummerUniekPerReeks($this->plaats->reeks_id);

        $this->assertFalse($validationRule->passes("nummer", 66));
        $this->assertEquals($validationRule->message(), "Nummer bestaat reeds voor reeks!");
    }

    /** @test  */
    public function validationSucceeds()
    {
        $validationRule = new NummerUniekPerReeks($this->plaats->reeks_id);

        $this->assertTrue($validationRule->passes("nummer", "67"));
    }
}
