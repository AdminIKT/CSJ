<?php

namespace App\Http\Requests\Movement;

use Illuminate\Foundation\Http\FormRequest;
use App\Entities\Order,
    App\Entities\Charge,
    App\Entities\OrderCharge,
    App\Entities\InvoiceCharge;

class ImportChargeRequest extends FormRequest
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
            'item.*.credit'      => 'required|min:1', 
            'item.*.invoice'     => 'required', 
            'item.*.invoiceDate' => 'required|date', 
            'item.*.hzyear'      => 'required|int',
            'item.*.hzentry'     => 'required',
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
            foreach ($data['item'] as $i => $values) {
                switch ($values['charge']) {
                    case OrderCharge::HZ_PREFIX:
                        if (!(isset($values['order']) && $values['order'] !== null)) {
                            $validator->errors()
                                      ->add("item.{$i}.order", __('Required field'));
                        }
                        if (!(isset($values['supplier']) && $values['supplier'] !== null)) {
                            $validator->errors()
                                      ->add("item.{$i}.supplier", __('Required field'));
                        }
                        break;
                    case InvoiceCharge::HZ_PREFIX:
                        if (!(isset($values['account']) && $values['account'] !== null)) {
                            $validator->errors()
                                      ->add("item.{$i}.account", __('Required field'));
                        }
                        break;
                }
            }
        });
    }
}
