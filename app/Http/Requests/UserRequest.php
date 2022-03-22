<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email:rfc,dns',
            'name' => 'required|string|min:2|max:191',
            'surname' => 'sometimes|nullable|string|min:2|max:191',
            'phone' => 'sometimes|nullable|numeric|phone_number:6,50',
            "street" => "sometimes|nullable|string|min:2",
            "house" => "sometimes|nullable|string|min:2",
            "city" => "sometimes|nullable|string|min:2",
            "postcode" => "sometimes|nullable|regex:/\b\d{4,10}\b/",
            'card_number' => 'sometimes|nullable|digits_between:12,18',
            'birthday' => 'sometimes|nullable|date|before:today|date_format:Y-m-d',
        ];
    }

    public function messages()
    {
        return [
            'card_number.digits_between' => __('Enter the correct number'),
            'phone.phone_number' => Str::ucfirst(__('enter the correct phone number.')),
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '"' . Str::lower(__('Name')) . '"',
            'surname' => '"' . Str::lower(__('Surname')) . '"',
            'birthday' => '"' . Str::lower(__('Birthday')) . '"',
            'phone' => '"' . Str::lower(__('Phone Number')) . '"',
            'email' => '"' . Str::lower(__('Email')) . '"',
            'street' => '"' . Str::lower(__('Street')) . '"',
            'house' => '"' . Str::lower(__('House')) . '"',
            'postcode' => '"' . Str::lower(__('Postcode')) . '"',
            'city' => '"' . Str::lower(__('City')) . '"',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('phone') && !empty($this['phone'])) $this->merge([
            "phone" => Str::replace('+', '', $this['phone']),
        ]);
    }
}
