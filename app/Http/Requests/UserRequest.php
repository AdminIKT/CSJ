<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $entity = $this->route('user');
        return [
            'email' => [
                'required',
                'email',
                'regex:/^[\w|\.|\-|\_]+@fpsanjorge.com$/i',
                'unique:\App\Entities\User,email' . ($entity ? ",{$entity->getId()}" : ""),
            ],
            'roles' => 'required',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //'email.regex' => 'custom message',
        ];
    }

}
