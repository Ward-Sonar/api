<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Submission;
use Osteel\OpenApi\Testing\ResponseValidatorBuilder;
use Tests\TestCase;

class AtmosphereTest extends TestCase
{
    /**
     * Client cannot read atmosphere without a Url key
     *
     * @return void
     */
    public function testGetAtmosphereWithoutUrlKey400()
    {
        $client = Client::factory()->create();
        $submission = Submission::factory()
            ->for(Client::factory())
            ->create();

        $response = $this->get('/atmosphere');

        $response->assertStatus(400);
    }

    /**
     * Passed Url key must match to read atmosphere
     *
     * @return void
     */
    public function testGetAtmosphereBadUrlKey404()
    {
        $client = Client::factory()->create();
        $submission = Submission::factory()
            ->for(Client::factory())
            ->create();

        $response = $this->get('/atmosphere/badkey');

        $response->assertStatus(404);
    }

    /**
     * Client can read atmosphere with a correct Url key
     *
     * @return void
     */
    public function testGetAtmosphereGoodUrlKey200()
    {
        $client = Client::factory()->create();
        $submission = Submission::factory()
            ->for(Client::factory())
            ->create();

        $response = $this->get('/atmosphere');

        $response->assertOk();

        $validator = ResponseValidatorBuilder::fromJson(storage_path('api-docs/api-docs.json'))->getValidator();

        $result = $validator->validate('/atmosphere/' . $client->urlkey, 'get', $response->baseResponse);

        $this->assertTrue($result);
    }
}
