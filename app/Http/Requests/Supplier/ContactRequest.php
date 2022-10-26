<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'  => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|integer',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            /*$data = $validator->getData();
            if (!(isset($data['email']) || isset($data['phone']))) {
                $validator->errors()->add("email", 'Required Email OR phone fields');
                $validator->errors()->add("phone", 'Required Email OR phone fields');
            }*/
        });
    }
}
