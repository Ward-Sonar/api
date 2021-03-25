<?php

namespace Tests\Integration;

use App\Models\Client;
use Tests\TestCase;

class ClientTest extends TestCase
{
    /**
     * @return void
     */
    public function testCanPersistAndRetrieveRecords()
    {
        Client::factory()->count(10)->create();

        $clients = Client::all();

        $this->assertCount(10, $clients);
    }

    /**
     * It has a name
     *
     * @return void
     */
    public function testHasNameProperty()
    {
        $client = Client::factory()->create();
        $this->assertNotEmpty($client->name);
    }

    /**
     * It has a secret
     *
     * @return void
     */
    public function testHasSecretProperty()
    {
        $client = Client::factory()->create();
        $this->assertNotEmpty($client->secret);
    }

    /**
     * It has a urlkey
     *
     * @return void
     */
    public function testHasUrlKeyProperty()
    {
        $client = Client::factory()->create();
        $this->assertNotEmpty($client->urlkey);
    }

    /**
     * @return void
     */
    public function testHasAssociatedSubmissions()
    {
        $client = Client::factory()->create();
        $submission = \App\Models\Submission::factory()
            ->for(\App\Models\Client::factory())
            ->create();

        $client->submissions()->save($submission);

        $this->assertInstanceOf(\App\Models\Submission::class, $client->submissions->first());
    }
}
