<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
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
            'receiver_account_number'   => ['required', 'string'],
            'amount'                    => ['required', 'numeric', 'min:10'],
            'pin'                       => ['required', 'string', 'min:4'],
            'description'               => ['nullable', 'max:200'],
        ];
    }
}
