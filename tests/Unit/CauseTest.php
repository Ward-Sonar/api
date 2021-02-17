<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CauseTest extends TestCase
{
    /**
     * It has a name
     *
     * @return void
     */
    public function testHasTextProperty()
    {
        $cause = factory(Cause::class)->create();
        $this->assertNotEmpty($cause->text);
    }
}
