<?php

namespace App\Services;

use App\Dtos\UserDto;
use App\Exceptions\InvalidPinLengthException;
use App\Exceptions\PinNotSetException;
use App\Http\Requests\RegisterUserRequest;
use App\interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserService implements UserServiceInterface
{
    /**
     * @param UserDto $userDto
     * 
     * @return User
     */
    public function createUser(UserDto $userDto): User
    {
          return User::query()->create([
               'name'         => $userDto->getName(),
               'email'        => $userDto->getEmail(),
               'password'     => $userDto->getPassword(),
               'phone_number' => $userDto->getPhoneNumber(),
          ]);
    }

    /**
     * @param int $userId
     * 
     * @return User
     */
    public function getUserById(int $userId): User
    {
        $user = User::query()->where('id', $userId)->first();
        if ( !$user ) {
            throw new ModelNotFoundException('User not found');
        }

        return $user;
    }

    /**
     * @param User $user
     * @param string $pin
     * 
     * @return void
     */
    public function setupPin(User $user, string $pin): void 
    {
        if ( $this->hasSetPin($user) ) {
            throw new BadRequestException('Pin has already been set');
        }
        if ( strlen($pin) != 4 ) {
            throw new InvalidPinLengthException();
        }

        $user->pin = Hash::make($pin);
        $user->save();
    }

    /**
     * @param string $pin
     * 
     * @return bool
     */
    public function validatePin(int $userId, string $pin): bool 
    {
        $user = $this->getUserById($userId);
        if ( !$this->hasSetPin($user) ) {
            throw new PinNotSetException('Please set your pin');
        }

        return Hash::check($pin, $user->pin);
    }

    /**
     * @param User $user
     * 
     * @return bool
     */
    public function hasSetPin(User $user): bool 
    {
        return $user->pin != null;
    }

    public function hasAccountNumber(UserDto $userDto): bool
    {
        return $user->account()->exists();
    }
}
