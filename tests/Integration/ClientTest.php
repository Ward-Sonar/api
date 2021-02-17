<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    /**
     * @return void
     */
    public function testCanPersistAndRetrieveRecords()
    {
        factory(Client::class, 10)->create();

        $clients = Client::all();

        $this->assertCount(10, $clients);
    }

    /**
     * @return void
     */
    public function testHasAssociatedSubmissions()
    {
        $client = factory(Client::class)->create();
        $submission = factory(\App\Models\Submission::class)->create();

        $client->submissions()->save($submission);

        $this->assertInstanceOf(\App\Models\Submission::class, $client->submissions->first());
    }
}
