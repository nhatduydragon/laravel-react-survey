<?php

namespace App\interfaces;

use App\Dtos\UserDto;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface UserServiceInterface 
{
     public function createUser(UserDto $userDto): User;
     public function getUserById(int $userId): User;
     public function setupPin(User $user, string $pin): void;
     public function validatePin(int $userId, string $pin): bool;
     public function hasSetPin(User $user): bool;
}