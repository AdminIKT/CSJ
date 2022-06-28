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
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->middleware('can:view,area')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Area $area)
    {
        $ppg   = $request->input('perPage', Config('app.per_page'));
        $class = $request->input('movement', Movement::class);
        $collection = $this->em->getRepository($class)
                           ->search(array_merge(
                                $request->all(), 
                                ['area' => $area->getId()]
                            ), $ppg);

        $accounts = $area->getAccounts()->toArray();
        $accounts = array_combine(
            array_map(function($e) { return $e->getId(); }, $accounts),
            array_map(function($e) { return $e->getSerial(); }, $accounts),
        );

        return view('areas.movements', [
            'perPage'    => $ppg,
            'entity'     => $area,
            'collection' => $collection,
            'accounts'   => $accounts,
        ]);
    }
}
