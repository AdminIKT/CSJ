<?php

namespace App\Http\Controllers\Area;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController,
    App\Entities\Movement,
    App\Entities\Area;

class MovementController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Area $area)
    {
        $collection = $this->em->getRepository(Movement::class)->search(
            $request->input('sequence'),
            $request->input('from'),
            $request->input('to'),
            $area->getId(),
            $request->input('supplier'),
            $request->input('otype'),
            $request->input('mtype'),
            $request->input('sortBy', 'created'),
            $request->input('sort', 'desc')
        );

        return view('areas.movements', [
            'entity' => $area,
            'collection' => $collection,
        ]);
    }
}
