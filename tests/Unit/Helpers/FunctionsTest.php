<?php

namespace Tests\Unit\Helpers;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function App\Helpers\verwijderAccenten;

class FunctionsTest extends TestCase
{

    /** @test  */
    public function verwijdertAccentAigu()
    {
        $metAccenten = "´áéýúíó";
        $zonderAccenten = "aeyuio";

        $this->assertEquals($zonderAccenten, verwijderAccenten($metAccenten));
    }

    /** @test  */
    public function verwijdertAccentGrave()
    {
        $metAccenten = "`àèùìò";
        $zonderAccenten = "aeuio";

        $this->assertEquals($zonderAccenten, verwijderAccenten($metAccenten));
    }

    /** @test  */
    public function verwijdertAccentCirconflexe()
    {
        $metAccenten = "^âêûîô";
        $zonderAccenten = "aeuio";

        $this->assertEquals($zonderAccenten, verwijderAccenten($metAccenten));
    }

    /** @test  */
    public function verwijdertTrema()
    {
        $metAccenten = "¨äëÿüïö";
        $zonderAccenten = "aeyuio";

        $this->assertEquals($zonderAccenten, verwijderAccenten($metAccenten));
    }

    /** @test  */
    public function verwijdertTilde()
    {
        $metAccenten = "~ãõñ";
        $zonderAccenten = "aon";

        $this->assertEquals($zonderAccenten, verwijderAccenten($metAccenten));
    }

    /** @test  */
    public function verwijdertCedille()
    {
        $metAccenten = "ç";
        $zonderAccenten = "c";

        $this->assertEquals($zonderAccenten, verwijderAccenten($metAccenten));
    }
}
