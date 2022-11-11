<?php

namespace App\Http\Requests;

use App\Entities\Account;
use App\Entities\Subaccount;

class OrderPostRequest extends OrderPutRequest 
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        if (null === ($entity = $this->em->find(Subaccount::class, $this->route('subaccount')))) {
            abort(404);
        }

        return array_merge(
            parent::rules(), [
                'estimatedCredit'     => "required|numeric|between:0,{$entity->getAvailableCredit()}",
                'estimated'           => 'mimes:pdf',
                'supplier'            => 'required',
            ]);
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $limit = $this->em->getRepository(\App\Entities\Settings::class)
                          ->findOneBy(['type' => \App\Entities\Settings::TYPE_ORDER_ESTIMATED_LIMIT]);

        $validator->after(function ($validator) use ($limit) {
            $data = $validator->getData();
            if (isset($data['custom']) && $data['custom']) {
                if (!isset($data['sequence']) || is_null($data['sequence']))
                $validator->errors()->add('sequence', __('Required field'));
            }
            if (!isset($data['estimated'])) {
                if ($data['estimatedCredit'] >= $limit->getValue()) {
                    $validator->errors()->add('estimated', __('Required field'));
                }
                elseif(!isset($data['products'])) {
                    $validator->errors()->add('estimated', __('If not present elements are required'));
                }     
            }
        });
    }
}
