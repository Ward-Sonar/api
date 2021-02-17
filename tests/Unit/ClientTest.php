<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    /**
     * It has a name
     *
     * @return void
     */
    public function testHasNameProperty()
    {
        $client = factory(Client::class)->create();
        $this->assertNotEmpty($client->name);
    }

    /**
     * It has a secret
     *
     * @return void
     */
    public function testHasSecretProperty()
    {
        $client = factory(Client::class)->create();
        $this->assertNotEmpty($client->secret);
    }

    /**
     * It has a urlkey
     *
     * @return void
     */
    public function testHasUrlKeyProperty()
    {
        $client = factory(Client::class)->create();
        $this->assertNotEmpty($client->urlkey);
    }
}
