<?php

namespace App\Http\Controllers\Account;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController,
    App\Entities\InvoiceCharge,
    App\Entities\Account;

class InvoiceChargeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Account $account)
    {
        $collection = $this->em->getRepository(InvoiceCharge::class)->search(
            $request->input('sequence'),
            $request->input('from'),
            $request->input('to'),
            $account->getId(),
            $request->input('supplier'),
            $request->input('otype'),
            $request->input('mtype'),
            $request->input('sortBy', 'created'),
            $request->input('sort', 'desc')
        );

        return view('accounts.invoiceCharges', [
            'entity' => $account,
            'collection' => $collection,
        ]);
    }
}
