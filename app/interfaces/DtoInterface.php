<?php 
namespace App\interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

interface DtoInterface
{
     public static function fromAPIFormRequest(FormRequest $request): self;
     public static function fromModel(Model $model): self;
     public static function toArray(Model $model): array;
}
