<?php

namespace Tests\Feature\Console;

use App\Models\Client;
use Illuminate\Support\Str;
use Tests\TestCase;

class ClientTest extends TestCase
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
     * Create a client
     *
     * @return void
     */
    public function testCreateClient()
    {
        $this->artisan('ws:createClient', ['name' => 'Ward A'])
            ->expectsOutput('Created Client Ward A')
            ->assertExitCode(0);

        $this->assertDatabaseHas('clients', [
            'name' => 'Ward A',
        ]);
    }
}
