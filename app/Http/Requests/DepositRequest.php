<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepositRequest extends FormRequest
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
            'account_number' => [ 'required', 'string', 'min:10' ],
            'amount'         => [ 'required', 'numeric' ],
            'description'    => [ 'required', 'string', 'min:5' ],
        ];
    }
}
