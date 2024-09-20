<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'date' => 'required|date',
            'customer' => 'required|exists:customer_infos,id',
            'mechanic' => 'required|exists:mechanic_infos,id',
            'earn_mechanic_percentage' => 'required|numeric|min:0|max:100',
            'plate' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'parts.*.damage_count' => 'required|integer|min:0',
            'car_size' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'customer.required' => 'Please select a customer.',
            'mechanic.required' => 'Please select a mechanic.',
            'earn_mechanic_percentage.required' => 'Mechanic earnings percentage is required.',
        ];
    }
}
