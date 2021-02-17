<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;

class CauseTest extends TestCase
{
    /**
     * @return void
     */
    public function testCanPersistAndRetrieveRecords()
    {
        factory(Cause::class, 10)->create();

        $causes = Cause::all();

        $this->assertCount(10, $causes);
    }

    /**
     * @return void
     */
    public function testHasAssociatedSubmissions()
    {
        $cause = factory(Cause::class)->create();
        $submission = factory(\App\Models\Submission::class)->create();

        $cause->submissions()->attach($submission->id);

        $this->assertInstanceOf(\App\Models\Submission::class, $cause->submissions->first());
    }
}
