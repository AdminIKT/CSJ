<?php

namespace App\Http\Controllers\Account;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Controllers\BaseController,
    App\Entities\InvoiceCharge,
    App\Entities\Movement,
    App\Entities\Account,
    App\Entities\Supplier;

class MovementController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->middleware('can:view,account')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Account $account)
    {
        $this->authorize('view', $account);

        $ppg   = $request->input('perPage', Config('app.per_page'));
        $class = $request->input('supplier') ?
            InvoiceCharge::class : 
            $request->input('movement', Movement::class);
        $collection = $this->em->getRepository($class)
                           ->search(array_merge(
                                $request->all(), 
                                ['account' => $account->getId()]
                            ), $ppg);

        $suppliers = $this->em->getRepository(Supplier::class)
                         ->search([
                            'account'  => $account->getId(),
                            'invoices' => true
                            ], null)
                         ->items();

        return view('accounts.movements', [
            'perPage'    => $ppg,
            'entity'     => $account,
            'collection' => $collection,
            'suppliers'  => Arr::pluck($suppliers, 'name', 'id'),
        ]);
    }
}
