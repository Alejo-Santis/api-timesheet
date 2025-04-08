<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimesheetRequest extends FormRequest
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
            'type' => 'required|in:work,absence',
            'day_in' => 'required|date|before:day_out',
            'day_out' => 'nullable|date|after:day_in',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'type' => 'type',
            'day_in' => 'start time',
            'day_out' => 'end time',
        ];
    }
    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.required' => 'The :attribute field is required.',
            'day_in.required' => 'The :attribute field is required.',
            'day_in.before' => 'The :attribute must be before the end time.',
            'day_out.after' => 'The :attribute must be after the start time.',
        ];
    }
}
