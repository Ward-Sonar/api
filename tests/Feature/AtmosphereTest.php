<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Submission;
use Illuminate\Support\Str;
use Osteel\OpenApi\Testing\ResponseValidatorBuilder;
use Tests\TestCase;

class AtmosphereTest extends TestCase
{
    /**
     * Client
     *
     * @var App\Models\Client
     **/
    protected $client;

    /**
     * Plain text client secret
     *
     * @var string
     **/
    protected $client_secret;

    public function setUp(): void
    {
        parent::setUp();

        $this->client_secret = Str::random(60);

        $this->client = Client::factory()->create([
            'secret' => hash('sha256', $this->client_secret),
        ]);
    }

    /**
     * Client requires a secret to read atmosphere
     *
     * @return void
     */
    public function testGetAtmosphereWithoutSecret401()
    {
        $response = $this->getJson(
            '/api/' . $this->version . '/atmosphere/' . $this->client->urlkey,
        );

        $response->assertStatus(401);
    }

    /**
     * Client requires a valid secret to read atmosphere
     *
     * @return void
     */
    public function testGetAtmosphereWithBadSecret401()
    {
        $response = $this->getJson(
            '/api/' . $this->version . '/atmosphere/badsecret',
        );

        $response->assertStatus(401);
    }

    /**
     * Client cannot read atmosphere without a Url key
     *
     * @return void
     */
    public function testGetAtmosphereWithoutUrlKey404()
    {
        $submission = Submission::factory()
            ->for($this->client)
            ->create();

        $response = $this->getJson(
            '/api/' . $this->version . '/atmosphere',
            [
                'Authorization' => 'Bearer ' . $this->client_secret,
            ]
        );

        $response->assertStatus(404);
    }

    /**
     * Passed Url key must match to read atmosphere
     *
     * @return void
     */
    public function testGetAtmosphereBadUrlKey404()
    {
        $submission = Submission::factory()
            ->for($this->client)
            ->create();

        $response = $this->getJson(
            '/api/' . $this->version . '/atmosphere/badkey',
            [
                'Authorization' => 'Bearer ' . $this->client_secret,
            ]
        );

        $response->assertStatus(404);
    }

    /**
     * Client can read atmosphere with a correct Url key
     *
     * @return void
     */
    public function testGetAtmosphereGoodUrlKey200()
    {
        $submission = Submission::factory()
            ->for($this->client)
            ->create();

        $response = $this->getJson(
            '/api/' . $this->version . '/atmosphere/' . $this->client->urlkey,
            [
                'Authorization' => 'Bearer ' . $this->client_secret,
            ]
        );

        $response->assertOk();

        $validator = ResponseValidatorBuilder::fromJson(storage_path('api-docs/api-docs.json'))->getValidator();

        $result = $validator->validate(
            '/api/' . $this->version . '/atmosphere/' . $this->client->urlkey,
            'get',
            $response->baseResponse
        );

        $this->assertTrue($result);
    }
}
