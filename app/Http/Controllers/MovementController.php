<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entities\Movement,
    App\Entities\Order,
    App\Entities\Account,
    App\Events\MovementEvent,
    App\Http\Requests\MovementRequest;

class MovementController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $collection = $this->em->getRepository(Movement::class)->search(
            $request->input('sequence'),
            $request->input('from'),
            $request->input('to'),
            $request->input('account'),
            $request->input('supplier'),
            $request->input('otype'),
            $request->input('mtype'),
            $request->input('sortBy', 'created'),
            $request->input('sort', 'desc')
        );

        $accounts  = $this->em->getRepository(Account::class)->findBy([], ['name' => 'ASC']);
        $accounts  = array_combine(
            array_map(function($e) { return $e->getId(); }, $accounts),
            array_map(function($e) { return "{$e->getName()}-{$e->getType()}"; }, $accounts),
        );

        return view('movements.index', [
            'collection' => $collection,
            'accounts' => $accounts,
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('movements.create', [
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MovementRequest $request)
    {
        $data = $request->validated();
        $description = $data['detail'];
        $matches = [];
        if (!preg_match(Order::SEQUENCE_PATTERN, $description, $matches)) {
            throw new \RuntimeException(sprintf("Description not matches with $pattern pattern"));
        }

        $order = $this->em->getRepository(Order::class)
                          ->findOneBy(['sequence' => $matches[0]]);

        if (!$order) {
            return redirect()->back()
                             ->withInput()
                             ->withErrors(["detail" => "Order {$matches[0]} not found"]);
        }
        else if (!$order->isStatus(Order::STATUS_CREATED)) {
            return redirect()->back()
                             ->withInput()
                             ->withErrors(["detail" => "Order status is {$order->getStatusName()}"]);
        }

        $movement = new Movement;
        $movement->setCredit($data['credit'])
                 ->setInvoice($data['invoice'])
                 ->setDetail(str_replace($matches[0], "", $description))
                 ->setOrder($order);

        MovementEvent::dispatch($movement);

        $this->em->persist($movement);
        $this->em->flush();

        return redirect()->route('movements.index')
                         ->with('success', 'Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
