<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Doctrine\ORM\EntityManagerInterface;
use App\Entities\Account;

class AccountPutRequest extends FormRequest
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
            'name'    => 'required|max:255',
            'users'   => 'required',
            'status'  => 'required',
            'detail'  => 'nullable',
            'users.0' => 'required',
            'users.*' => 'required|distinct',
        ];
    }
}
