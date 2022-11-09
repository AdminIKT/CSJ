<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AreaPostRequest extends FormRequest
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
        $entity = $this->route('area');
        return [
            'name' => 'required|max:255',
            'acronym' => 'required|max:3|unique:\App\Entities\Area,acronym' . ($entity ? ",{$entity->getId()}" : ""),
        ];
    }   
}
