<?php

namespace Tests\Feature\Console;

use App\Models\Cause;
use Tests\TestCase;

class CauseTest extends TestCase
{
    /**
     * Create a cause and return it's ID
     *
     * @return void
     */
    public function testCreateCause()
    {
        $this->artisan('ws:createCause')
            ->expectsQuestion('Describe the cause of a change in ward atmosphere', 'Cause A')
            ->assertExitCode(0);

        $this->assertDatabaseHas('causes', [
            'text' => 'Cause A',
        ]);
    }
}
