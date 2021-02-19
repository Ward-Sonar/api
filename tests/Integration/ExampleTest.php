<?php

namespace Tests\Integration;

use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        User::factory()->count(10)->create();

        $users = User::all();

        $this->assertCount(10, $users);
    }
}
