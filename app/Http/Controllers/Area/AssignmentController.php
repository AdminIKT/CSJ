<?php

namespace App\Http\Controllers\Area;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController,
    App\Entities\Assignment,
    App\Entities\Area;

class AssignmentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Area $area)
    {
        $years = $this->em->getRepository(Assignment::class)->years($area);
        $years = array_combine($years, $years);
        $collection = $this->em->getRepository(Assignment::class)->search(
            $request->input('year'),
            $area->getId(),
            $request->input('type'),
            $request->input('operator'),
            $request->input('credit'),
            $request->input('sortBy', 'created'),
            $request->input('sort', 'desc')
        );

        return view('areas.assignments', [
            'years'  => $years,
            'entity' => $area,
            'collection' => $collection,
        ]);
    }
}
