<?php

namespace Tests\Feature\Api;

use App\Models\Kalender;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateKalenderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dummyTest()
    {
        $this->assertTrue(true);
    }

//    /** @test */
    public function nietIngelogdeGebruikerKanGeenKalenderAanmaken()
    {
        $response = $this->post('api/kalenders', []);

        $response->assertRedirect('login');
    }

//    /** @test */
    public function ingelogdeGebruikerKanKalenderAanmaken()
    {
        $this->actingAs(User::factory()->create());
        $kalender = maakKalender();

        $response = $this->post('api/kalenders', $kalender->toArray());

        $response->assertStatus(201);
        $this
            ->assertDatabaseHas(
                'kalenders',
                $kalender->toArray()
            )
            ->assertJson($kalender->toJson())
        ;
    }

//    /** @test */
    public function jaarIsVerplicht() {
        $expectedErrorMessage = "Jaar is verplicht!";
        $this->actingAs(User::factory()->create());
        $kalender = maakKalender(['jaar' => null]);

        $response = $this->postJson('api/kalenders', $kalender->toArray());

        $this->assertEquals(errorMessage("jaar", $response), $expectedErrorMessage);
    }
}
