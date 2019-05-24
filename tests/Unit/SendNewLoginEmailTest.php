<?php

namespace SamuelNitsche\AuthLog\Tests\Unit;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use SamuelNitsche\AuthLog\Notifications\NewDevice;
use SamuelNitsche\AuthLog\Tests\Models\User;
use SamuelNitsche\AuthLog\Tests\TestCase;

class SendNewLoginEmailTest extends TestCase
{
    /** @test */
    public function it_notifies_users_about_a_new_login()
    {
        Notification::fake();

        $user = User::create(['name' => 'JohnDoe', 'email' => 'john@example.com']);

        Auth::login($user);

        Notification::assertSentTo($user, NewDevice::class);
    }

    /** @test */
    public function it_does_not_notify_users_about_a_second_login_from_the_same_device()
    {
        Notification::fake();

        $user = User::create(['name' => 'JohnDoe', 'email' => 'john@example.com']);

        Auth::login($user);

        Notification::assertSentTo($user, NewDevice::class);

        Auth::logout();

        Notification::fake();

        Auth::login($user);

        Notification::assertNothingSent();
    }

    /** @test */
    public function it_does_not_notify_when_notifications_are_disabled()
    {
        config(['auth-log.notify' => false]);

        Notification::fake();

        $user = User::create(['name' => 'JohnDoe', 'email' => 'john@example.com']);

        Auth::login($user);

        Notification::assertNothingSent();
    }
}
