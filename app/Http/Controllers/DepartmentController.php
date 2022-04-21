<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entities\Department,
    App\Http\Requests\DepartmentPostRequest;

class DepartmentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = $this->em->getRepository(Department::class)->findAll();

        return view('departments.index', [
            'collection' => $departments,
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('departments.form', [
            'route' => route('departments.store'),
            'method' => 'POST',
            'entity' => new Department,
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  DepartmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentPostRequest $request)
    {
        $data = $request->validated();
        $dptm = new Department;
        $dptm->setName($data['name'])
             ->setAcronym($data['acronym']);

        $this->em->persist($dptm);
        $this->em->flush();
        return redirect()->route('departments.index')
                         ->with('success', 'Successfully created');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        return view('departments.show', [
            'entity' => $department,
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        return view('departments.form', [
            'route' => route('departments.update', ['department' => $department->getId()]),
            'method' => 'PUT',
            'entity' => $department,
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentPostRequest $request, Department $department)
    {
        $data = $request->validated();
        $department->setName($data['name'])
                   ->setAcronym($data['acronym']);

        $this->em->flush();
        return redirect()->route('departments.index')
                         ->with('success', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $this->em->remove($department);
        $this->em->flush();

        return redirect()->back()->with('success', 'Successfully removed');
    }
}
