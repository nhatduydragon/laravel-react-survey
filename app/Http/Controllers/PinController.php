<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PinController extends Controller
{
    public function __construct(private UserService $userService)
    {

    }

    public function setPin(Request $request): JsonResponse
    {
        $this->validate($request, [
            'pin' => [ 'required', 'string', 'min:4', 'max:4' ],
        ]);

        /** @var \app\models\User $user */
        $user = $request->user();
        $this->userService->setupPin( $user, $request->input('pin') );

        return $this->sendSuccess([], 'Pin is set successfully');
    }

    public function validatePin(Request $request): JsonResponse
    {
        $this->validate($request, [
            'pin' => [ 'required', 'string' ],
        ]);

        /** @var \app\models\User $user */
        $user = $request->user();
        $isValid = $this->userService->validatePin( $user->id, $request->input('pin') );

        return $this->sendSuccess([ 'is_valid' => $isValid ], 'Pin Validation');
    }
}
