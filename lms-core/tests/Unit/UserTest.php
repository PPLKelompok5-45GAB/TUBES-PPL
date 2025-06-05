<?php

namespace Tests\Unit;

use App\Models\User;

class UserTest extends UnitTestCase
{
    public function test_user_factory_creates_user(): void
    {
        $user = \App\Models\User::factory()->create();
        $this->assertInstanceOf(\App\Models\User::class, $user);
    }
}
