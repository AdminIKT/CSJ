<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Events\AssignmentEvent,
    App\Entities\Assignment,
    App\Entities\Area;

class AssignmentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $years = $this->em->getRepository(Assignment::class)->years();
        $years = array_combine($years, $years);

        $areas = $this->em->getRepository(Area::class)->findBy([], ['name' => 'ASC']);
        $areas = array_combine(
            array_map(function($e) { return $e->getId(); }, $areas),
            array_map(function($e) { return "{$e->getName()}-{$e->getType()}"; }, $areas),
        );

        $collection = $this->em->getRepository(Assignment::class)->search(
            $request->input('year'),
            $request->input('area'),
            $request->input('type'),
            $request->input('operator'),
            $request->input('credit'),
            $request->input('sortBy', 'created'),
            $request->input('sort', 'desc')
        );

        return view('assignments.index', [
            'collection' => $collection,
            'years'      => $years,
            'areas'      => $areas,
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $entity = new Assignment;
        if (null !== ($id = $request->input('area')) && 
            null !== ($area = $this->em->find(Area::class, $id))) { 
            $entity->setArea($area);
            $areas = [$area->getId() => "{$area->getName()}-{$area->getType()}"];
        }
        else {
            $areas  = $this->em->getRepository(Area::class)->findBy([], ['name' => 'ASC']);
            $areas  = array_combine(
                array_map(function($e) { return $e->getId(); }, $areas),
                array_map(function($e) { return "{$e->getName()}-{$e->getType()}"; }, $areas),
            );
        }

        return view('assignments.form', [
            'dst'    => $request->input('destination'),
            'entity' => $entity,
            'areas'  => $areas,
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
            'detail' => ['max:255'],
        ]);

        $area = $this->em->find(Area::class, $values['area']); 
        $entity = new Assignment;
        $entity->setArea($area)
               ->setCredit($values['credit'])
               ->setType($values['type'])
               ->setDetail($values['detail']);

        AssignmentEvent::dispatch($entity, __FUNCTION__);

        $this->em->persist($entity);
        $this->em->flush();
        $dst = $request->get('destination', route('assignments.index'));
        return redirect()->to($dst)->with('success', 'Successfully created');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assignment $assignment)
    {
        AssignmentEvent::dispatch($assignment, __FUNCTION__);
        $this->em->remove($assignment);
        $this->em->flush();
        return redirect()->back()->with('success', 'Successfully removed');
    }
}
