<?php

namespace SamuelNitsche\AuthLog\Listeners;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Auth\Events\Login;
use SamuelNitsche\AuthLog\AuthLog;
use SamuelNitsche\AuthLog\Notifications\NewDevice;

class LogSuccessfulLogin
{
    /**
     * The request.
     *
     * @var Request
     */
    public $request;

    /**
     * Create the event listener.
     *
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        $ip = $this->request->ip();
        $userAgent = $this->request->userAgent();
        $known = $user->authentications()->whereIpAddress($ip)->whereUserAgent($userAgent)->first();

        $authenticationLog = new AuthLog([
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'login_at' => Carbon::now(),
        ]);

        $user->authentications()->save($authenticationLog);

        if (! $known && config('auth-log.notify')) {
            $user->notify(new NewDevice($authenticationLog));
        }
    }
}
