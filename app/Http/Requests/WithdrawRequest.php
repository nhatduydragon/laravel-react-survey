<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'amount'         => [ 'required', 'numeric' ],
            'description'    => [ 'required', 'string', 'min:5' ],
            'pin'            => [ 'required', 'string', 'min:4' ],
        ];
    }
}
