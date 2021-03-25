<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

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
        Artisan::call('config:clear');
        Artisan::call('route:clear');
    }
}
