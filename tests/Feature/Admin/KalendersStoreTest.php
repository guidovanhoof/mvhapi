<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KalendersStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function kalenderAanmaken()
    {
        $kalender = maakKalender();

        $response = $this->json(
            'POST',
            ADMIN_API_URL,
            ["jaar" => $kalender->jaar, "opmerkingen" => $kalender->opmerkingen]
        );

        $response->assertStatus(201);
        $this
            ->assertDatabaseHas(
                'kalenders',
                $kalender->toArray()
            )
            ->assertJson($kalender->toJson())
        ;
    }

    /** @test */
    public function jaarIsVerplicht() {
        $expectedErrorMessage = "Jaar is verplicht!";
        $kalender = maakKalender(['jaar' => null]);

        $response = $this->json(
            'POST',
            ADMIN_API_URL,
            ["jaar" => $kalender->jaar, "opmerkingen" => $kalender->opmerkingen]
        );

        $response->assertStatus(422);
        $this->assertEquals(errorMessage("jaar", $response), $expectedErrorMessage);
    }

    /** @test */
    public function jaarIsUniek() {
        $expectedErrorMessage = "Jaar bestaat reeds!";
        $kalender = bewaarKalender();

        //dd(["jaar" => $kalender->jaar, "opmerkingen" => $kalender->opmerkingen]);
        $response = $this->json(
            'POST',
            ADMIN_API_URL,
            ["jaar" => $kalender->jaar, "opmerkingen" => $kalender->opmerkingen]
        );

        $response->assertStatus(422);
        $this->assertEquals(errorMessage("jaar", $response), $expectedErrorMessage);
    }

    /** @test */
    public function opmerkingenIsOptioneel() {
        $kalender = maakKalender(["opmerkingen" => null]);

        $response = $this->post(
            ADMIN_API_URL,
            ["jaar" => $kalender->jaar, "opmerkingen" => $kalender->opmerkingen]
        );

        $response->assertStatus(201);
        $this
            ->assertDatabaseHas(
                'kalenders',
                $kalender->toArray()
            )
            ->assertJson($kalender->toJson())
        ;
    }
}
