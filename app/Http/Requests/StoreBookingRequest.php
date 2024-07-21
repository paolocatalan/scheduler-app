<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
            'schedule_call' => 'required|date|date_format:Y-m-d H:s:i|after:now|unique:App\Models\Booking',
            'timezone' => 'required|timezone:all',
            'name' => 'required',
            'email' => 'required|email:strict',
            'notes' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'schedule_call.required' => 'Something went wrong. Please go back and select a date again.',
            'schedule_call.unique' => 'Something went wrong. Please go back and select a date again.',
            'timezone.required' => 'Something really went wrong. Please go back and select a timezone again.',
            'name.required' => 'Name Required! Let\'s not be strangers!',
            'email.required' => 'We definitely need your email address!',
            'email.email' => 'Hmm, that doesn\'t look like a valid email.',
            'notes.required' => 'Hey! Additional notes needed. Got any fun facts or extra details?'
        ];
    }
}
