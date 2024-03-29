<?php

namespace Tests\Feature;

use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CanSeeProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_see_profile_test()
    {
        
        $user = User::factory()->create(['name' => 'test']);

        $this->actingAs($user)->get('@test')->assertSee('test');
    }
}
