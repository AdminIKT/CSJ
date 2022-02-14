<?php

namespace App\Http\Controllers\Area;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller,
    App\Entities\Movement,
    App\Entities\Area;

class MovementController extends Controller
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
    public function index(Area $area)
    {
        $collection = $this->em->getRepository(Movement::class)
                               ->fromArea($area);

        return view('areas.movements', [
            'entity' => $area,
            'collection' => $collection,
        ]);
    }
}