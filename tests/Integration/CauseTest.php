<?php

namespace Tests\Integration;

use App\Models\Cause;
use Tests\TestCase;

class CauseTest extends TestCase
{
    /**
     * @return void
     */
    public function testCanPersistAndRetrieveRecords()
    {
        Cause::factory()->count(10)->create();

        $causes = Cause::all();

        $this->assertCount(10, $causes);
    }

    /**
     * It has a name
     *
     * @return void
     */
    public function testHasTextProperty()
    {
        $cause = Cause::factory()->create();
        $this->assertNotEmpty($cause->text);
    }

    /**
     * @return void
     */
    public function testHasAssociatedSubmissions()
    {
        $cause = Cause::factory()->create();
        $submission = \App\Models\Submission::factory()
            ->for(\App\Models\Client::factory())
            ->create();

        $cause->submissions()->attach($submission->id);

        $this->assertInstanceOf(\App\Models\Submission::class, $cause->submissions->first());
    }
}
