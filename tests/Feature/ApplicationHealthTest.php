<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApplicationHealthTest extends TestCase
{
    public function test_application_boots_successfully(): void
    {
        $this->assertNotNull(app());
        $this->assertTrue(app()->bound('config'));
    }

    public function test_application_uses_testing_environment(): void
    {
        $this->assertTrue(app()->environment('testing'));
    }

    public function test_testing_cache_uses_array_driver(): void
    {
        $this->assertSame('array', config('cache.default'));
    }

    public function test_testing_session_uses_array_driver(): void
    {
        $this->assertSame('array', config('session.driver'));
    }
}