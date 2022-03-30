<?php

namespace App\Http\Requests;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Foundation\Http\FormRequest;
use App\Entities\Area;

class OrderPostRequest extends FormRequest
{
    /**
     * @EntityManagerInterface
     */ 
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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
        $id = $this->route('area');
        if (null === ($entity = $this->em->find(Area::class, $id))) {
            abort(404);
        }
        return [
            'estimatedCredit'     => "required|numeric|between:0,{$entity->getAvailableCredit()}",
            'estimated'           => 'mimes:pdf',
            'receiveIn'           => 'required',
            'products.*.supplier' => 'required',
            'products.*.detail'   => 'required|max:255',
            'products.*.credit'   => 'required|min:0',
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
        $limit = $this->em->getRepository(\App\Entities\Settings::class)
                          ->findOneBy(['type' => \App\Entities\Settings::TYPE_ESTIMATED_LIMIT]);

        $validator->after(function ($validator) use ($limit) {
            $data = $validator->getData();
            if (isset($data['custom']) && $data['custom']) {
                if (!isset($data['sequence']) || is_null($data['sequence']))
                $validator->errors()->add('sequence', 'Required field');
            }
            if (!isset($data['estimated']) && isset($data['estimatedCredit']) && $data['estimatedCredit']) {
                if ($data['estimatedCredit'] >= $limit->getValue()) {
                    $validator->errors()->add('estimated', 'Required field');
                }
            }
        });
    }
}
