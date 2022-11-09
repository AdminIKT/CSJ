<?php

namespace App\Http\Requests;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Foundation\Http\FormRequest;
use App\Entities\Account;
use App\Entities\Subaccount;

class OrderPutRequest extends FormRequest
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
            'receiveIn'         => 'required',
            'detail'            => 'nullable|max:255',
            'products.*.id'     => 'nullable|int',
            'products.*.detail' => 'required|max:255',
            'products.*.units'  => 'required|min:1',
        ];
    }
}
