<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Entities\Account,
    App\Entities\Order,
    App\Entities\Charge,
    App\Entities\InvoiceCharge,
    App\Entities\HzCharge,
    App\Entities\OrderCharge;

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
            'invoiceDate' => 'date',
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

                if (!preg_match(OrderCharge::HZ_PATTERN, $data['detail'], $matches)) {
                    $validator->errors()
                              ->add("detail", trans("Unmatched a valid charge :pattern pattern", [
                                    'pattern' => OrderCharge::HZ_PATTERN
                                ]));
                }
                else {
                    switch (strtoupper($matches[1])) {
                        case HzCharge::HZ_PREFIX:
                        case InvoiceCharge::HZ_PREFIX:                            
                            if (!preg_match(Account::SEQUENCE_PATTERN, $matches[2])) {
                                $validator->errors()->add("detail", trans("Unmatched a valid account :pattern pattern in :sequence", [
                                    'pattern'  => Account::SEQUENCE_PATTERN,
                                    'sequence' => $matches[2],
                                ]));
                            }
                            break;

                        case OrderCharge::HZ_PREFIX:
                            if (!preg_match(Order::SEQUENCE_PATTERN, $matches[2])) {
                                $validator->errors()->add("detail", trans("Unmatched a valid order :pattern pattern in :sequence", [
                                    'pattern'  => Order::SEQUENCE_PATTERN,
                                    'sequence' => $matches[2],
                                ]));
                            }
                            break;
                    }

                    if (strtoupper($matches[1])!=HzCharge::HZ_PREFIX) {
                        if (!(isset($data['invoice']) && $data['invoice'] !== null)) {
                            $validator->errors()
                                      ->add("invoice", __('Required field'));
                        }
                        if (!(isset($data['invoiceDate']) && $data['invoiceDate'] !== null)) {
                            $validator->errors()
                                      ->add("invoiceDate", __('Required field'));
                        }
                    }
                }
            }
        });
    }
}
