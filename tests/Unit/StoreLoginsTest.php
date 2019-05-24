<?php

namespace SamuelNitsche\AuthLog\Tests\Unit;

use Mockery;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use SamuelNitsche\AuthLog\Tests\Models\User;
use SamuelNitsche\AuthLog\Tests\TestCase;
use SamuelNitsche\AuthLog\Listeners\LogSuccessfulLogin;

class StoreLoginsTest extends TestCase
{
    /** @test */
    public function it_stores_logins_in_the_database()
    {
        $user = User::create(['name' => 'JohnDoe']);

        $login = new Login($user, false);

        Event::dispatch($login);

        $this->assertDatabaseHas('auth_log', [
            'authenticatable_id' => $user->id
        ]);
    }
}