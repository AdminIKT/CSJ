<?php

namespace App\Http\Controllers\Area;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller,
    App\Entities\Assignment,
    App\Entities\Area;

class AssignmentController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Area $area)
    {
        $collection = $this->em->getRepository(Assignment::class)->search(
        );

        return view('areas.assignments', [
            'entity' => $area,
            'collection' => $collection,
        ]);
    }
}
