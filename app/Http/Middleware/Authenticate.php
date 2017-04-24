<?php

namespace TargetInk\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use TargetInk\User;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check the instant login
        if ($request->has('i')) {
            $user = User::where('instant', $request->input('i'))->first();
            if($user) {
                $this->auth->login($user);
            }
        }

        // If we are still a guest, then redirect
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }

        return $next($request);
    }
}
