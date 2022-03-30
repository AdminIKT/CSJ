<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entities\Assignment,
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
    public function index(Request $request)
    {
        $collection = $this->em->getRepository(Assignment::class)
                           ->search();

        return view('assignments.index', [
            'collection' => $collection,
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $areas  = $this->em->getRepository(Area::class)->findBy([], ['name' => 'ASC']);
        $areas  = array_combine(
            array_map(function($e) { return $e->getId(); }, $areas),
            array_map(function($e) { return "{$e->getName()}-{$e->getType()}"; }, $areas),
        );

        return view('assignments.form', [
            'entity' => new Assignment,
            'areas' => $areas,
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $values = $request->validate([
            'area'   => ['required'],
            'type'   => ['required'],
            'credit' => ['required'],
        ]);

        $area = $this->em->find(Area::class, $values['area']); 
        $entity = new Assignment;
        $entity->setArea($area)
               ->setCredit($values['credit'])
               ->setType($values['type']);

        $this->em->persist($entity);
        $this->em->flush();
        return redirect()->route('assignments.index')
                         ->with('success', 'Successfully created');
    }
}
