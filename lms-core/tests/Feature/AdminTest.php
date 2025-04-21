<?php

namespace Tests\Unit;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_factory_creates_admin(): void
    {
        $admin = Admin::factory()->create();
        $this->assertInstanceOf(Admin::class, $admin);
    }
}
