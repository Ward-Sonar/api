<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;

class SubmissionTest extends TestCase
{
    /**
     * @return void
     */
    public function testCanPersistAndRetrieveRecords()
    {
        factory(Submission::class, 10)->create();

        $submissions = Submission::all();

        $this->assertCount(10, $submissions);
    }

    /**
     * @return void
     */
    public function testHasAssociatedCauses()
    {
        $submission = factory(Submission::class)->create();
        $cause = factory(\App\Models\Cause::class)->create();

        $submission->causes()->attach($cause->id);

        $this->assertInstanceOf(\App\Models\Cause::class, $submission->causes->first());
    }

    /**
     * @return void
     */
    public function testHasAssociatedClient()
    {
        $submission = factory(Submission::class)->create();
        $client = factory(\App\Models\Client::class)->create();

        $submission->client()->associate($client);

        $this->assertInstanceOf(\App\Models\Client::class, $submission->client);
    }
}
