<?php

namespace SamuelNitsche\AuthLog\Tests\Unit;

use Illuminate\Auth\Events\Logout;
use Mockery;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Event;
use SamuelNitsche\AuthLog\Listeners\LogSuccessfulLogin;
use SamuelNitsche\AuthLog\Listeners\LogSuccessfulLogout;
use SamuelNitsche\AuthLog\Tests\TestCase;

class AuthLogServiceProviderTest extends TestCase
{
    /** @test */
    public function it_registers_the_login_listener()
    {
        $listener = Mockery::spy(LogSuccessfulLogin::class);
        app()->instance(LogSuccessfulLogin::class, $listener);

        $user = new User();
        $login = new Login($user, false);

        Event::dispatch($login);

        $listener->shouldHaveReceived('handle')->with(Mockery::on(function ($event) use ($user) {
            return $event->user === $user;
        }));
    }

    /** @test */
    public function it_registers_the_logout_listener()
    {
        $listener = Mockery::spy(LogSuccessfulLogout::class);
        app()->instance(LogSuccessfulLogout::class, $listener);

        $user = new User();
        $logout = new Logout($user);

        Event::dispatch($logout);

        $listener->shouldHaveReceived('handle')->with(Mockery::on(function ($event) use ($user) {
            return $event->user === $user;
        }));
    }
}