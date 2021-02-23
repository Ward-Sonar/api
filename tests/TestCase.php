<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * API version
     *
     * @var string
     **/
    protected $version = 'v1';

    public function setUp(): void
    {
        parent::setUp();
    }
}
