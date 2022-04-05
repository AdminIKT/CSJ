<?php

namespace App\Http\Controllers\Supplier;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController,
    App\Entities\Area,
    App\Entities\Movement,
    App\Entities\Supplier;

class MovementController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Supplier $supplier)
    {
        $collection = $this->em->getRepository(Movement::class)->search(
            $request->input('sequence'),
            $request->input('from'),
            $request->input('to'),
            $request->input('area'),
            $supplier->getId(),
            $request->input('otype'),
            $request->input('mtype'),
            $request->input('sortBy', 'created'),
            $request->input('sort', 'desc')
        );

        $areas  = $this->em->getRepository(Area::class)->findBy([], ['name' => 'ASC']);
        $areas  = array_combine(
            array_map(function($e) { return $e->getId(); }, $areas),
            array_map(function($e) { return "{$e->getName()}-{$e->getType()}"; }, $areas),
        );

        return view('suppliers.movements', [
            'entity'     => $supplier,
            'areas'      => $areas,
            'collection' => $collection,
        ]);
    }
}
