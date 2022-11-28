<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Entities\Account,
    App\Entities\Order,
    App\Entities\Charge,
    App\Entities\InvoiceCharge;

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
            'detail'      => 'required|max:255',
            'invoice'     => 'required|max:255',
            'invoiceDate' => 'required',
            'credit'      => 'required|min:0',
            'hzyear'      => 'required|int',
            'hzentry'     => 'required',
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

                if (!preg_match(InvoiceCharge::HZ_PATTERN, $data['detail'], $matches)) {
                    $validator->errors()
                              ->add("detail", "Unmatched a valid sequence pattern");
                }
                else {
                    $detail = substr($matches[0], 2);
                    switch (strtoupper($matches[1])) {
                        case Charge::HZ_PREFIX:
                            if (!preg_match(Account::SEQUENCE_PATTERN, $detail)) {
                                $validator->errors()->add("detail", "Unmatched an account sequence pattern");
                            }
                            break;
                        case InvoiceCharge::HZ_PREFIX:
                            if (!preg_match(Order::SEQUENCE_PATTERN, $detail)) {
                                $validator->errors()->add("detail", "Unmatched an order sequence pattern");
                            }
                            break;
                    }
                }
            }
        });
    }
}
