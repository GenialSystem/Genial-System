<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'assigned_cars' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'admin_name' => 'required|string|max:255',
            'cellphone' => 'required|string|max:30',
            'email-customer' => 'required|email|max:255',
            'rag_sociale' => 'required|string|max:255',
            'iva' => 'required|string|max:100',
            'pec' => 'required|string|max:255',
            'sdi' => 'required|string|max:100',
            // 'address' => 'required|string|max:255',
            'legal_address' => 'required|string|max:255',
            'cap' => 'required|string|max:100',
            'province' => 'required|string|max:255',
            'password-customer' => 'required|string|max:255',
        ];
    }
}
