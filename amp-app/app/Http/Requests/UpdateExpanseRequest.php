<?php

namespace App\Http\Requests;

use App\Rules\InsufficientBalanceRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdateExpanseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id'),
            ],
            'type_id' => [
                'nullable',
            ],
            'unit_id' => [
                'nullable',
            ],
            'details' => 'nullable|string|max:500',
            'receipt_no' => 'nullable|string|max:30',
            'amount' => ['required', 'numeric', 'between:0.01,9999999999', new InsufficientBalanceRule],
            'unit_value' => 'nullable|numeric|between:0.01,99999999',
            'date' => 'date',
            'notes' => 'nullable|string',
            'status' => 'nullable|digits_between:0,1',
            'attachment' => [
                'nullable',
                File::types(['png', 'jpeg', 'pdf', 'doc', 'docx'])->max(1024), // KB
            ],
        ];
    }
}
