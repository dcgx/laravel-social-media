<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Status;
use App\Providers\RouteServiceProvider;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanLikeStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_users_cannot_like_statuses()
    {
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser) use ($status) {
            $browser->visit(RouteServiceProvider::HOME)
                    ->waitForText($status->body)
                    ->press('@btn-like')
                    ->assertPathIs('/login');
        });
    }

    /** @test */
    public function users_can_like_and_unlike_statuses()
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $status) {
            $browser->loginAs($user)
                    ->visit(RouteServiceProvider::HOME)
                    ->waitForText($status->body)
                    
                    ->assertSeeIn('@likes-count', 0) // se especifica un elemento
                    
                    ->press('@btn-like')
                    ->waitForText('TE GUSTA')
                    ->assertSee('TE GUSTA')
                    ->assertSeeIn('@likes-count', 1)

                    ->press('@btn-like')
                    ->waitForText('ME GUSTA')
                    ->assertSee('ME GUSTA')
                    ->assertSeeIn('@likes-count', 0);
        });
    }

    /** @test */
    public function users_can_see_likes_and_unlikes_in_real_time()
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser1, Browser $browser2) use ($user, $status) {
            $browser1->visit('/home');

            $browser2->loginAs($user)
                    ->visit(RouteServiceProvider::HOME)
                    ->waitForText($status->body)
                    ->assertSeeIn('@likes-count', 0) // se especifica un elemento
                    ->press('@btn-like')
                    ->waitForText('TE GUSTA')
            ;

            $browser1->assertSeeIn('@likes-count', 1);

            $browser2->press('@btn-like')
                    ->waitForText('ME GUSTA');

            $browser1->assertSeeIn('@likes-count', 0);
        });
    }
}
