<?php

namespace App\Http\Requests;

use App\Enum\TranscactionCategoryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterTransactionRequest extends FormRequest
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
            'start_date' => [ Rule::requiredIf( request()->query('end_date') != null ), 'date_format:Y-m-d' ],
            'end_date'   => [ Rule::requiredIf( request()->query('start_date') != null ), 'date_format:Y-m-d' ],
            'category'   => [ 'nullable', 'string', Rule::in( TranscactionCategoryEnum::DEPOSIT, TranscactionCategoryEnum::WITHDRAWAL ) ],
            'per_page'   => [ 'nullable', 'integer', 'min:10', 'max:100' ],
        ];
    }
}
