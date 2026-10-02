<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    protected $redirect = '/#contact';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'not_regex:/[\r\n]/'],
            'email' => ['required', 'email:rfc', 'max:254'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[+0-9().\s-]+$/'],
            'company' => ['nullable', 'string', 'max:150'],
            'project_type' => ['required', Rule::in(config('portfolio.project_types'))],
            'budget' => ['nullable', Rule::in(config('portfolio.budgets'))],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
            'website' => ['nullable', 'string', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.min' => 'Please share a little more about your project (at least 20 characters).',
            'website.max' => 'We could not submit this form. Please leave the website field empty.',
            'phone.regex' => 'Please enter a valid phone or WhatsApp number.',
        ];
    }
}
