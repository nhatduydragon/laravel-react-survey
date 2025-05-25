<?php 

namespace App\Dtos;

use App\Http\Requests\RegisterUserRequest;
use App\interfaces\DtoInterface;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class UserDto implements DtoInterface
{
     private ?int $id;
     private string $name;
     private string $email;
     private ?string $pin;
     private string $phone_number;
     private string $password;
     private ?Carbon $created_at;
     private ?Carbon $updated_at;

     public function getId():  ?int
     {
          return $this->id;
     }

     public function setId(int $id): void
     {
          $this->id = $id;
     }

     public function getName(): string
     {
          return $this->name;
     }

     public function setName(string $name): void
     {
          $this->name = $name;
     }

     public function getEmail():  ?string
     {
          return $this->email;
     }

     public function setEmail(string $email): void
     {
          $this->email = $email;
     }

     public function getPhoneNumber():  string
     {
          return $this->phone_number;
     }

     public function setPhoneNumber(string $phone_number): void
     {
          $this->phone_number = $phone_number;
     }

     public function getPassword():  string
     {
          return $this->password;
     }

     public function setPassword(string $password): void
     {
          $this->password = $password;
     }

     public function getPin():  string
     {
          return $this->pin;
     }

     public function setPin(string $pin): void
     {
          $this->pin = $pin;
     }

     public function getUpdatedAt():  ?Carbon
     {
          return $this->updated_at;
     }

     public function setUpdatedAt(Carbon $updated_at): void
     {
          $this->updated_at = $updated_at;
     }

     public function getCreatedAt():  ?Carbon
     {
          return $this->created_at;
     }

     public function setCreatedAt(Carbon $created_at): void
     {
          $this->created_at = $created_at;
     }

     public static function fromAPIFormRequest(FormRequest $request): DtoInterface 
     {
          $userDto = new UserDto();
          $userDto->setName($request->input('name'));
          $userDto->setEmail($request->input('email'));
          $userDto->setPassword($request->input('password'));
          $userDto->setPhoneNumber($request->input('phone_number'));

          return $userDto;
     }

     public static function fromModel(User|Model $model): UserDto
     {
          $userDto = new UserDto();
          $userDto->setId($model->id);
          $userDto->setName($model->name);
          $userDto->setEmail($model->email);
          $userDto->setPhoneNumber($model->phone_number);
          $userDto->setCreatedAt($model->created_at);
          $userDto->setUpdatedAt($model->updated_at);

          return $userDto;
     }

     public static function toArray(Model|User $model): array 
     {
          return [
               'name'         => $model->name,
               'email'        => $model->email,
               'id'           => $model->id,
               'phone_number' => $model->phone_number,
               'created_at'   => $model->created_at,
               'updated_at'   => $model->updated_at,
          ];
     }
}
