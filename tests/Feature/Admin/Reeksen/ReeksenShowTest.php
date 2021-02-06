<?php

namespace Tests\Feature\Admin\Reeksen;

use App\Models\Kalender;
use App\Models\Reeks;
use App\Models\Wedstrijdtype;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ReeksenShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function reeksNietAanwezig()
    {
        $response = $this->getReeks('666');

        $response->assertStatus(404);
        $data = $response->json();
        $this->assertEquals("Reeks niet gevonden!", $data["message"]);
    }

    /** @test */
    public function reeksAanwezig()
    {
        $reeks = bewaarReeks();

        $response = $this->getReeks($reeks->id);

        $response->assertStatus(200);
        $data = $response->json();
        assertReeksEquals($this, $data, $reeks);
    }

    /**
     * @param $id
     * @return TestResponse
     */
    private function getReeks($id): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'GET',
                    URL_REEKSEN_ADMIN . $id
                )
        ;
    }
}