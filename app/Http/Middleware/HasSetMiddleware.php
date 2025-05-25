<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\UserService;
use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HasSetMiddleware
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = $request->user();
        $userService = new UserService();
        if ( !$userService->hasSetPin($user) ) {
            return $this->sendError('Please set your pin', Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
