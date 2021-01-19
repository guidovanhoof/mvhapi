<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

const USER_EMAIL = 'abc@def.com';
const USER_PASSWORD = 'password';

class ApiAuthTest extends TestCase
{
    private $plainTextToken = "";

    use RefreshDatabase;

    /** @test */
    public function geldigEmailEnCorrectWachtwoord()
    {
        $response = $this->login(
            ["email" => USER_EMAIL, "password" => USER_PASSWORD]
        );

        $response->assertStatus(200);
        $tokenInfo = $response->json()["token"];
        $this->assertEquals("Bearer", $tokenInfo["type"]);
        $this->assertEquals($this->plainTextToken, $tokenInfo["token"]);
    }

    /** @test */
    public function geldigEmailEnFoutiefWachtwoord()
    {
        createUserAndToken();

        $response = $this->login(
            ["email" => USER_EMAIL, "password" => "passw0rd"]
        );

        $response->assertStatus(401);
        $message = $response->json()["message"];
        $this->assertEquals("Combinatie email/wachtwoord ongeldig!", $message);
    }

    /** @test */
    public function ongeldigEmailEnCorrectWachtwoord()
    {
        $response = $this->login(
            ["email" => USER_EMAIL . "abc", "password" => USER_PASSWORD]
        );

        $response->assertStatus(401);
        $message = $response->json()["message"];
        $this->assertEquals("Combinatie email/wachtwoord ongeldig!", $message);
    }

    /** @test */
    public function emailIsVerplicht()
    {
        $response = $this->login([]);

        $response->assertStatus(422);
        $errors = $response->json()["errors"];
        $this->assertEquals("Email is verplicht!", $errors["email"][0]);
    }

    /** @test */
    public function emailMoetGeldigZijn()
    {
        $response = $this->login(["email" => "test"]);

        $response->assertStatus(422);
        $errors = $response->json()["errors"];
        $this->assertEquals("Email moet een geldig email adres zijn!", $errors["email"][0]);
    }

    /** @test */
    public function wachtwoordIsVerplicht()
    {
        $response = $this->login(["email" => "email@email.com"]);

        $response->assertStatus(422);
        $errors = $response->json()["errors"];
        $this->assertEquals("Wachtwoord is verplicht!", $errors["password"][0]);
    }

    /**
     * @param $data
     * @return TestResponse
     */
    public function login($data): TestResponse
    {
        bewaarUser(["email" => USER_EMAIL]);

        $response =
            $this
                ->json(
                    'POST',
                    'api/token/create',
                    $data,
                )
        ;
        $this->rememberPlainTextToken($response);

        return $response;
    }

    /**
     * @param TestResponse $response
     */
    private function rememberPlainTextToken(TestResponse $response): void
    {
        if ($response->status() === 200) {
            $this->plainTextToken = $response->json()["token"]["token"];
        } else {
            $this->plainTextToken = "";
        }
    }
}
