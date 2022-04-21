<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AreaRequest extends FormRequest
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
            'acronym' => 'required|max:3',
            'accounts' => 'required',
            'detail' => 'nullable',
            //'credit' => 'required',
            //'lcode' => 'integer',
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
            if (isset($data['type']) && $data['type'] === \App\Entities\Area::TYPE_LANBIDE) {
                if (!isset($data['lcode']) || is_null($data['lcode']))
                $validator->errors()->add('lcode', 'Required field');
            }
        });
    }
}
