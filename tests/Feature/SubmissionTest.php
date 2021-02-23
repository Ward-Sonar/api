<?php

namespace Tests\Feature;

use App\Models\Client;
use Illuminate\Support\Str;
use Osteel\OpenApi\Testing\ResponseValidatorBuilder;
use Tests\TestCase;

class SubmissionTest extends TestCase
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
     * Client requires a secret to add a submission
     *
     * @return void
     */
    public function testPostSubmissionWithoutSecret401()
    {
        $response = $this->postJson(
            '/api/' . $this->version . '/submission',
            [
                'data' => [
                    'attributes' => [
                        'abandoned' => true,
                    ],
                ],
            ]
        );

        $response->assertStatus(401);
    }

    /**
     * Client secret must be valid to add a submission
     *
     * @return void
     */
    public function testPostSubmissionBadSecret401()
    {
        $response = $this->postJson(
            '/api/' . $this->version . '/submission',
            [
                'data' => [
                    'attributes' => [
                        'abandoned' => true,
                    ],
                ],
            ],
            [
                'Authorization' => 'Bearer badsecret',
            ]
        );

        $response->assertStatus(401);
    }

    /**
     * Client can add a submission with a good secret
     *
     * @return void
     */
    public function testPostSubmissionGoodSecret201()
    {
        $response = $this->postJson(
            '/api/' . $this->version . '/submission',
            [
                'data' => [
                    'attributes' => [
                        'abandoned' => true,
                    ],
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->client_secret,
            ]
        );

        $response->assertStatus(201);
    }

    /**
     * Client must provide minumum data for a submission
     *
     * @return void
     */
    public function testPostSubmissionMissingData400()
    {
        $response = $this->postJson(
            '/api/' . $this->version . '/submission',
            [
                'data' => [
                    'attributes' => [
                    ],
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->client_secret,
            ]
        );

        $response->assertStatus(400);
    }

    /**
     * Client must provide atmosphere data that meets validation for a submission
     *
     * @return void
     */
    public function testPostSubmissionBadAtmosphereData400()
    {
        $response = $this->postJson(
            '/api/' . $this->version . '/submission',
            [
                'data' => [
                    'attributes' => [
                        'abandoned' => false,
                        'atmosphere' => -3,
                        'direction' => 0,
                        'comment' => 'this is a comment',
                    ],
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->client_secret,
            ]
        );

        $response->assertStatus(400);

        $response = $this->postJson(
            '/api/' . $this->version . '/submission',
            [
                'data' => [
                    'attributes' => [
                        'abandoned' => false,
                        'atmosphere' => 3,
                        'direction' => 0,
                        'comment' => 'this is a comment',
                    ],
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->client_secret,
            ]
        );

        $response->assertStatus(400);
    }

    /**
     * Client must provide direction data that meets validation for a submission
     *
     * @return void
     */
    public function testPostSubmissionBadDirectionData400()
    {
        $response = $this->postJson(
            '/api/' . $this->version . '/submission',
            [
                'data' => [
                    'attributes' => [
                        'abandoned' => false,
                        'atmosphere' => 0,
                        'direction' => -2,
                        'comment' => 'this is a comment',
                    ],
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->client_secret,
            ]
        );

        $response->assertStatus(400);

        $response = $this->postJson(
            '/api/' . $this->version . '/submission',
            [
                'data' => [
                    'attributes' => [
                        'abandoned' => false,
                        'atmosphere' => 0,
                        'direction' => 2,
                        'comment' => 'this is a comment',
                    ],
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->client_secret,
            ]
        );

        $response->assertStatus(400);
    }

    /**
     * Client can upload additional data and they will be recorded
     *
     * @return void
     */
    public function testPostSubmissionFullData201()
    {
        $response = $this->postJson(
            '/api/' . $this->version . '/submission',
            [
                'data' => [
                    'attributes' => [
                        'abandoned' => false,
                        'atmosphere' => -1,
                        'direction' => 0,
                        'comment' => 'this is a comment',
                    ],
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->client_secret,
            ]
        );

        $response->assertStatus(201);

        $this->assertDatabaseHas('submissions', [
            'id' => $client->id,
            'abandoned' => false,
            'atmosphere' => -1,
            'direction' => 0,
            'comment' => 'this is a comment',
        ]);

        $validator = ResponseValidatorBuilder::fromJson(storage_path('api-docs/api-docs.json'))->getValidator();

        $result = $validator->validate('/api/' . $this->version . '/submission', 'post', $response->baseResponse);

        $this->assertTrue($result);
    }
}
