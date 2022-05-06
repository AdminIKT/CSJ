<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Entities\Account;

class AccountPostRequest extends FormRequest
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
            'name' => 'required|max:255',
            'type' => 'required',
            'lcode' => 'integer',
            'acronym' => 'required|max:3',
            'accounts' => 'required',
            'users' => 'required',
            'detail' => 'nullable',
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
            $data = $validator->getData();
            if (isset($data['type']) && 
                in_array($data['type'], [Account::TYPE_LANBIDE, Account::TYPE_OTHER])
            ) {
                if (!isset($data['lcode']) || is_null($data['lcode']))
                $validator->errors()->add('lcode', 'Required field');
            }
        });
    }
}
