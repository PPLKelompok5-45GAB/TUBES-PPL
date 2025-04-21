<?php

namespace Tests\Unit;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_factory_creates_member(): void
    {
        $member = \App\Models\Member::factory()->create();
        $this->assertInstanceOf(\App\Models\Member::class, $member);
    }
}
