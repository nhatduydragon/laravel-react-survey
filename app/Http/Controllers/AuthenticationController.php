<?php

namespace App\Http\Controllers;

use App\Dtos\UserDto;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function __construct(private UserService $userService) 
    {

    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $userDto = UserDto::fromAPIFormRequest($request);
        $user    = $this->userService->createUser($userDto);

        return $this->sendSuccess(['user' => $user], "Registration Successfully");
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentails = $request->validated();
        if ( !Auth::attempt($credentails) ) {
            return $this->sendError('The provided credentials are incorrect.');
        }

        /** @var \app\models\User $user */
        $user  = $request->user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->sendSuccess([
                'user' => $user, 
                'token' => $token
            ], 'Log in successfully');
    }

    public function user(Request $request): JsonResponse
    {
        return $this->sendSuccess([
            'user' => $request->user(),
        ], 'Authenticated user retrieved');
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();

        return $this->sendSuccess([], 'Logged out successfully');
    }
}
