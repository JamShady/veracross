<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Models\Contact as ContactModel;
use Illuminate\Foundation\Http\FormRequest;

class Contact extends FormRequest
{

    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'max:50',
            ],
            'last_name' => [
                'required',
                'string',
                'max:50',
            ],
            'DOB' => [
                'nullable',
                'date',
            ],
            'company_name' => [
                'required',
                'string',
                'max:100',
            ],
            'position' => [
                'required',
                'string',
                'max:100',
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique(ContactModel::class)
                    ->ignore($this->route('contact')),
            ],
            'number' => [
                'array',
                'min:1',
                function($attribute, $value, $fail) {
                    if (!collect($value)->filter()->isNotEmpty()) {
                        $fail('At least one phone number must be provided.');
                    }
                },
            ],
            'number.*' => [
                'nullable',
                'string',
                'max:15',
                'regex:/^\+?\d+$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'number.*.string' => 'Please enter a value for Phone Number #:position',
            'number.*.regex' => 'Phone Number #:position must contain only numbers with an optional + at the start',
        ];
    }


    public function __get($key): mixed
    {
        return $key === 'number'
            ? array_filter(parent::__get($key))
            : parent::__get($key);
    }

}
