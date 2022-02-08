<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
            'nif' => 'integer|required',
            'zip' => 'integer|required',
            'name' => 'required|max:255',
            'city' => 'required|max:255',
            'contacts.*.name'  => 'required|max:255',
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
            if (!(isset($data['contacts']) && is_array($data['contacts']))) return;
            foreach ($data['contacts'] as $i => $raw) {
                if (!(isset($raw['email']) || isset($raw['phone']))) {
                    $validator->errors()->add("contacts.{$i}.email", 'Required Email OR phone fields');
                    $validator->errors()->add("contacts.{$i}.phone", 'Required Email OR phone fields');
                }
            }
        });
    }
}
