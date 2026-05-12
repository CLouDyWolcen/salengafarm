<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'account_type' => ['required', 'in:individual,company'],
        ];

        // Conditional validation based on account type
        if ($this->account_type === 'company') {
            $rules = array_merge($rules, [
                'company_name' => ['required', 'string', 'max:255'],
                'company_address' => ['required', 'string', 'max:500'],
                'company_city' => ['required', 'string', 'max:255'],
                'company_province' => ['required', 'string', 'max:255'],
                'company_zip_code' => ['required', 'string', 'max:20'],
                'company_contact_person' => ['required', 'string', 'max:255'],
                'position' => ['required', 'string', 'max:255'],
                'company_phone_number' => ['required', 'string', 'max:255'],
                'business_type' => ['required', 'string', 'max:255'],
                'business_type_other' => ['nullable', 'required_if:business_type,other', 'string', 'max:255'],
                'tin' => ['nullable', 'string', 'max:255'],
                'website_socials' => ['nullable', 'string', 'max:255'],
            ]);
        } else {
            // Individual account
            $rules = array_merge($rules, [
                'address' => ['required', 'string', 'max:500'],
                'city' => ['required', 'string', 'max:255'],
                'province' => ['required', 'string', 'max:255'],
                'zip_code' => ['required', 'string', 'max:20'],
                'property_type' => ['required', 'string', 'max:255'],
                'property_type_other' => ['nullable', 'required_if:property_type,other', 'string', 'max:255'],
                'gender' => ['nullable', 'in:male,female,other'],
            ]);
        }

        return $rules;
    }
}
