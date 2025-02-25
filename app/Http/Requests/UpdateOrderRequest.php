<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
    public function rules()
    {
        return [
            'car_size' => 'required|string',
            'replacements' => 'required|string',
            'color' => 'required|string',
            
            // 'earn_mechanic_percentage' => 'required|integer',
            'aluminium' => 'nullable',
            'parts.*.damage_count' => 'required|integer|min:0',
            'parts.*.name' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'car_size.required' => 'Please select the vehicle size.',
            'replacements.required' => 'The replacements field is required.',
            'notes.required' => 'Please add notes.',
            'parts.*.damage_count.required' => 'Please provide the damage count for each part.',
            'parts.*.damage_count.integer' => 'The damage count must be a valid number.',
            'parts.*.damage_count.min' => 'The damage count must be greater than or equal to 0.',
            'parts.*.name.required' => 'The part name is required.',
        ];
    }
    
}
