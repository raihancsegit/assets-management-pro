<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryRequest extends FormRequest
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
            'category_id' => 'required',
            'details' => 'nullable|string|max:500',
            'name' => 'required|string|unique:types|max:50',
            'value_amount' => 'nullable|numeric|between:0.01,9999999999',
            'color' => 'nullable|string|max:30',
            'serial' => 'nullable|max:30',
            'inventorie_type' => 'nullable|max:30',
            'shade_no' => 'nullable|max:30',
        ];
    }
}
