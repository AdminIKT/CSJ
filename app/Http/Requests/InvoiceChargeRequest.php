<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Entities\Order;

class InvoiceChargeRequest extends FormRequest
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
            'detail'  => 'required|max:255',
            'invoice' => 'required|max:255',
            'invoiceDate' => 'required',
            'credit'  => 'required|min:0',
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
            if (isset($data['detail'])) {
                $matches = [];
                if (!preg_match(Order::SEQUENCE_PATTERN, $data['detail'])) {
                    $validator->errors()->add("detail", "Unmatched an order sequence pattern");
                }
            }
        });
    }
}
