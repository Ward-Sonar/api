<?php

namespace Tests\Unit;

use App\Models\Submission;
use PHPUnit\Framework\TestCase;

class SubmissionTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasACausesMethod()
    {
        $submission = new Submission();
        $this->assertTrue(method_exists($submission, 'causes'));
    }

    /**
     * @return void
     */
    public function testHasAClientMethod()
    {
        $submission = new Submission();
        $this->assertTrue(method_exists($submission, 'client'));
    }
}
