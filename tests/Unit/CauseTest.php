<?php

namespace Tests\Unit;

use App\Models\Cause;
use PHPUnit\Framework\TestCase;

class CauseTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasASubmissionsMethod()
    {
        $cause = new Cause();
        $this->assertTrue(method_exists($cause, 'submissions'));
    }
}
