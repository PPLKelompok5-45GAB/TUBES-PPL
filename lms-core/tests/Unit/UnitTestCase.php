<?php

namespace Tests\Unit;

use Tests\Feature\Traits\UseSimpleDatabaseSetup;
use Tests\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnitTestCase extends BaseTestCase
{
    use RefreshDatabase, UseSimpleDatabaseSetup;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupSimpleDatabase();
    }
}
