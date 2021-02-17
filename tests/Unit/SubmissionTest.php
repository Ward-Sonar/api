<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SubmissionTest extends TestCase
{
    /**
     * It has an atmosphere
     *
     * @return void
     */
    public function testHasAtmosphereProperty()
    {
        $submission = factory(Submission::class)->create();
        $this->assertNotEmpty($submission->atmosphere);
    }

    /**
     * It has a direction
     *
     * @return void
     */
    public function testHasDirectionProperty()
    {
        $submission = factory(Submission::class)->create();
        $this->assertNotEmpty($submission->direction);
    }

    /**
     * It has a comment
     *
     * @return void
     */
    public function testHasCommentProperty()
    {
        $submission = factory(Submission::class)->create();
        $this->assertNotEmpty($submission->comment);
    }

    /**
     * It has an abandoned
     *
     * @return void
     */
    public function testHasAbandonedProperty()
    {
        $submission = factory(Submission::class)->create();
        $this->assertNotEmpty($submission->abandoned);
    }

    /**
     * It has a client_id
     *
     * @return void
     */
    public function testHasClientIdProperty()
    {
        $submission = factory(Submission::class)->create();
        $this->assertNotEmpty($submission->client_id);
    }
}
