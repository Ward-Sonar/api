<?php

namespace Tests\Unit;

use App\Models\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasASubmissionsMethod()
    {
        $client = new Client();
        $this->assertTrue(method_exists($client, 'submissions'));
    }
}
