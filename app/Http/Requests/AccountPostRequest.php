<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Entities\Account;
use Doctrine\ORM\EntityManagerInterface;

class AccountPostRequest extends FormRequest
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
        return [
            'name' => 'required|max:255',
            'type' => 'required',
            'lcode' => 'integer',
            'acronym' => 'required|max:3',
            'accounts.0' => 'required',
            'accounts.*' => 'required|distinct',
            'users' => 'required',
            'detail' => 'nullable',
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
            
            $baldintzak =  [];

            if(isset($data['type']) && isset($data['acronym'])){
                $baldintzak['type'] = $data['type'];
                $baldintzak['acronym'] = $data['acronym'];
            }

            if (isset($data['type']) && 
                in_array($data['type'], [Account::TYPE_LANBIDE, Account::TYPE_OTHER])
            ) {
                if (!isset($data['lcode']) || is_null($data['lcode']))
                    $validator->errors()->add('lcode', __('Required field'));
                else
                    $baldintzak['lcode'] = $data['lcode'];
            }

            $existe = $this->em->getRepository(\App\Entities\Account::class)
            ->findOneBy($baldintzak);

            if($existe) {
                $validator->errors()->add('acronym', __('Acronym exists'));               
            }

        });
    }
}
