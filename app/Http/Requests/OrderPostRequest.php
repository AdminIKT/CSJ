<?php

namespace App\Http\Requests;

use App\Entities\Order;
use App\Entities\Account;
use App\Entities\Settings;
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
                'sequence'            => 'unique:\App\Entities\Order,sequence',
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
        $limit = $this->em->getRepository(Settings::class)
                          ->findOneBy(['type' => \App\Entities\Settings::TYPE_ORDER_ESTIMATED_LIMIT
                          ]);

        $validator->after(function ($validator) use ($limit) {
            $data = $validator->getData();
            if (isset($data['custom']) && $data['custom']) {
                if (!isset($data['sequence']) || is_null($data['sequence'])) {
                    $validator->errors()->add('sequence', __('Required field'));
                }
                if (!isset($data['date']) || is_null($data['date'])) {
                    $validator->errors()->add('date', __('Required field'));
                }
                else {
                    $date = new \DateTime($data['date']);
                    if (!isset($data['previous'])) {
                        $validator->errors()->add('previous', __('Required field'));
                    }
                    else {
                        $prev = $this->em->getRepository(Order::class)
                                ->findOneBy(['id' => $data['previous']]);

                        if ($date->format('Y-m-d') < $prev->getDate()->format('Y-m-d')) {
                            $validator->errors()->add('date', __('Must be greather than :date', [
                                'date' => $prev->getDate()->format('d/m/Y')
                            ]));
                        }

                        $qb = $this->em->createQueryBuilder();
                        $qb->select('o')
                           ->from(Order::class, 'o')
                           ->join('o.subaccount', 's')
                           ->andwhere('s.account = :account')
                           ->andwhere('o.date > :date')
                           ->setParameter('account', $prev->getAccount())
                           ->setParameter('date', $prev->getDate())
                           ->orderBy('o.date', 'ASC')
                           ->setMaxResults(1);

                        $next = $qb->getQuery()->getOneOrNullResult();
                        if ($next && $date->format('Y-m-d') > $next->getDate()->format('Y-m-d')) {
                            $validator->errors()->add('date', __('Must be less than :date', [
                                'date' => $next->getDate()->format('d/m/Y')
                            ]));
                        }
                        //dd($date, $prev, $next);
                    }
                }
            }

            if (!isset($data['estimated'])) {
                if ($data['estimatedCredit'] >= $limit->getValue()) {
                    $validator->errors()->add('estimated', __('Required field'));
                }
                elseif(!isset($data['products'])) {
                    $validator->errors()->add('estimated', __('If not present, elements are required'));
                }     
            }
        });
    }
}
