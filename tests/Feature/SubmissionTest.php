<?php

namespace Tests\Feature;

use App\Models\Client;
use Osteel\OpenApi\Testing\ResponseValidatorBuilder;
use Tests\TestCase;

class SubmissionTest extends TestCase
{
    /**
     * Client requires a secret to add a submission
     *
     * @return void
     */
    public function testPostSubmissionWithoutSecret401()
    {
        $client = Client::factory()->create();

        $response = $this->postJson('/submission', [
            'data' => [
                'attributes' => [
                    'abandoned' => true,
                ],
            ],
        ]);

        $response->assertStatus(401);
    }

    /**
     * Client secret must be valid to add a submission
     *
     * @return void
     */
    public function testPostSubmissionBadSecret401()
    {
        $client = Client::factory()->create();

        $response = $this->withHeaders([
            'X-CLIENT-SECRET' => base64_encode('badsecret'),
        ])->postJson('/submission', [
            'data' => [
                'attributes' => [
                    'abandoned' => true,
                ],
            ],
        ]);

        $response->assertStatus(401);
    }

    /**
     * Client can add a submission with a good secret
     *
     * @return void
     */
    public function testPostSubmissionGoodSecret201()
    {
        $client = Client::factory()->create();

        $response = $this->withHeaders([
            'X-CLIENT-SECRET' => base64_encode($client->secret),
        ])->postJson('/submission', [
            'data' => [
                'attributes' => [
                    'abandoned' => true,
                ],
            ],
        ]);

        $response->assertStatus(201);
    }

    /**
     * Client must provide minumum data for a submission
     *
     * @return void
     */
    public function testPostSubmissionMissingData400()
    {
        $client = Client::factory()->create();

        $response = $this->withHeaders([
            'X-CLIENT-SECRET' => base64_encode($client->secret),
        ])->postJson('/submission', [
            'data' => [
                'attributes' => [
                ],
            ],
        ]);

        $response->assertStatus(400);
    }

    /**
     * Client must provide atmosphere data that meets validation for a submission
     *
     * @return void
     */
    public function testPostSubmissionBadAtmosphereData400()
    {
        $client = Client::factory()->create();

        $response = $this->withHeaders([
            'X-CLIENT-SECRET' => base64_encode($client->secret),
        ])->postJson('/submission', [
            'data' => [
                'attributes' => [
                    'abandoned' => false,
                    'atmosphere' => -3,
                    'direction' => 0,
                    'comment' => 'this is a comment',
                ],
            ],
        ]);

        $response->assertStatus(400);

        $response = $this->withHeaders([
            'X-CLIENT-SECRET' => base64_encode($client->secret),
        ])->postJson('/submission', [
            'data' => [
                'attributes' => [
                    'abandoned' => false,
                    'atmosphere' => 3,
                    'direction' => 0,
                    'comment' => 'this is a comment',
                ],
            ],
        ]);

        $response->assertStatus(400);
    }

    /**
     * Client must provide direction data that meets validation for a submission
     *
     * @return void
     */
    public function testPostSubmissionBadDirectionData400()
    {
        $client = Client::factory()->create();

        $response = $this->withHeaders([
            'X-CLIENT-SECRET' => base64_encode($client->secret),
        ])->postJson('/submission', [
            'data' => [
                'attributes' => [
                    'abandoned' => false,
                    'atmosphere' => 0,
                    'direction' => -2,
                    'comment' => 'this is a comment',
                ],
            ],
        ]);

        $response->assertStatus(400);

        $response = $this->withHeaders([
            'X-CLIENT-SECRET' => base64_encode($client->secret),
        ])->postJson('/submission', [
            'data' => [
                'attributes' => [
                    'abandoned' => false,
                    'atmosphere' => 0,
                    'direction' => 2,
                    'comment' => 'this is a comment',
                ],
            ],
        ]);

        $response->assertStatus(400);
    }

    /**
     * Client can upload additional data and they will be recorded
     *
     * @return void
     */
    public function testPostSubmissionFullData201()
    {
        $client = Client::factory()->create();

        $response = $this->withHeaders([
            'X-CLIENT-SECRET' => base64_encode($client->secret),
        ])->postJson('/submission', [
            'data' => [
                'attributes' => [
                    'abandoned' => false,
                    'atmosphere' => -1,
                    'direction' => 0,
                    'comment' => 'this is a comment',
                ],
            ],
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('submissions', [
            'id' => $client->id,
            'abandoned' => false,
            'atmosphere' => -1,
            'direction' => 0,
            'comment' => 'this is a comment',
        ]);

        $validator = ResponseValidatorBuilder::fromJson(storage_path('api-docs/api-docs.json'))->getValidator();

        $result = $validator->validate('/submission', 'post', $response->baseResponse);

        $this->assertTrue($result);
    }
}
