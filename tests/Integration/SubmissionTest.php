<?php

namespace Tests\Integration;

use App\Models\Submission;
use Tests\TestCase;

class SubmissionTest extends TestCase
{
    /**
     * @return void
     */
    public function testCanPersistAndRetrieveRecords()
    {
        Submission::factory()
            ->count(10)
            ->for(\App\Models\Client::factory())
            ->create();

        $submissions = Submission::all();

        $this->assertCount(10, $submissions);
    }

    /**
     * It has an atmosphere
     *
     * @return void
     */
    public function testHasAtmosphereProperty()
    {
        $submission = Submission::factory()
            ->for(\App\Models\Client::factory())
            ->create();
        $this->assertIsNumeric($submission->atmosphere);
    }

    /**
     * It has a direction
     *
     * @return void
     */
    public function testHasDirectionProperty()
    {
        $submission = Submission::factory()
            ->for(\App\Models\Client::factory())
            ->create();
        $this->assertIsNumeric($submission->direction);
    }

    /**
     * It has a comment
     *
     * @return void
     */
    public function testHasCommentProperty()
    {
        $submission = Submission::factory()
            ->for(\App\Models\Client::factory())
            ->create();
        $this->assertNotEmpty($submission->comment);
    }

    /**
     * It has an abandoned
     *
     * @return void
     */
    public function testHasAbandonedProperty()
    {
        $submission = Submission::factory()
            ->for(\App\Models\Client::factory())
            ->create();
        $this->assertIsBool($submission->abandoned);
    }

    /**
     * It has a client_id
     *
     * @return void
     */
    public function testHasClientIdProperty()
    {
        $submission = Submission::factory()
            ->for(\App\Models\Client::factory())
            ->create();
        $this->assertNotEmpty($submission->client_id);
    }

    /**
     * @return void
     */
    public function testHasAssociatedCauses()
    {
        $submission = Submission::factory()
            ->for(\App\Models\Client::factory())
            ->create();
        $cause = \App\Models\Cause::factory()->create();

        $submission->causes()->attach($cause->id);

        $this->assertInstanceOf(\App\Models\Cause::class, $submission->causes->first());
    }

    /**
     * @return void
     */
    public function testHasAssociatedClient()
    {
        $submission = Submission::factory()
            ->for(\App\Models\Client::factory())
            ->create();
        $client = \App\Models\Client::factory()->create();

        $submission->client()->associate($client);

        $this->assertInstanceOf(\App\Models\Client::class, $submission->client);
    }
}
