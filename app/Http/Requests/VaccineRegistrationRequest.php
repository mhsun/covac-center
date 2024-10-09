<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VaccineRegistrationRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'nid' => 'required|numeric|unique:users',
            'vaccine_center_id' => 'required|exists:vaccine_centers,id',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'A registered user already exists with this email address.',
            'nid.unique' => 'A registered user already exists with this NID.',
        ];
    }
}
