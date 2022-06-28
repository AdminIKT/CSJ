<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entities\Area,
    App\Http\Requests\AreaPostRequest;

class AreaController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->authorizeResource(Area::class, 'area');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas = $this->em->getRepository(Area::class)
                          ->findBy([], ['acronym' => 'desc']);

        return view('areas.index', [
            'collection' => $areas,
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('areas.form', [
            'route' => route('areas.store'),
            'method' => 'POST',
            'entity' => new Area,
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AreaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AreaPostRequest $request)
    {
        $data = $request->validated();
        $dptm = new Area;
        $dptm->setName($data['name'])
             ->setAcronym($data['acronym']);

        $this->em->persist($dptm);
        $this->em->flush();
        return redirect()->route('areas.index')
                         ->with('success', 'Successfully created');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Area $area)
    {
        return view('areas.show', [
            'entity' => $area,
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $area)
    {
        return view('areas.form', [
            'route' => route('areas.update', ['area' => $area->getId()]),
            'method' => 'PUT',
            'entity' => $area,
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AreaPostRequest $request, Area $area)
    {
        $data = $request->validated();
        $area->setName($data['name'])
                   ->setAcronym($data['acronym']);

        $this->em->flush();
        return redirect()->route('areas.index')
                         ->with('success', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $area)
    {
        $this->em->remove($area);
        $this->em->flush();

        return redirect()->back()->with('success', 'Successfully removed');
    }
}
