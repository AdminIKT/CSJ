<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Doctrine\ORM\EntityManagerInterface;

use App\Entities\Area,
    App\Entities\User,
    App\Entities\Order,
    App\Entities\Department,
    App\Http\Requests\AreaRequest;

class AreaController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $areas = $this->em->getRepository(Area::class)->search(
            $request->input('name'),
            $request->input('type'),
            $request->input('creditOp'),
            $request->input('credit'),
            $request->input('compromisedOp'),
            $request->input('compromised'),
            $request->input('sortBy', 'name'),
            $request->input('sort', 'desc')
        );

        return view('areas.index', [
            'areas' => $areas,
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = $this->em->getRepository(Department::class)
                                ->findBy([], ['name' => 'asc']);
        $users = $this->em->getRepository(User::class)
                                ->findBy([], ['email' => 'asc']);

        return view('areas.form', [
            'route' => route('areas.store'),
            'method' => 'POST',
            'entity' => new Area,
            'users' => $users,
            'departments' => Arr::pluck($departments, 'name', 'id'),
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AreaRequest $request)
    {
        //$data = $request->validated();
        $entity = new Area;
        $this->hydrateData($entity, $request->all());
        $this->em->persist($entity);
        $this->em->flush();
        return redirect()->route('areas.show', ['area' => $entity->getId()])
                         ->with('success', 'Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Area $area)
    {
        $orders = $this->em->getRepository(Order::class)->search(
            $request->input('sequence'),
            $request->input('from'),
            $request->input('to'),
            $request->input('department'),
            $area->getId(),
            $request->input('supplier'),
            $request->input('type'),
            $request->input('status'),
            $request->input('sortBy', 'date'),
            $request->input('sort', 'desc')
        );

        return view('areas.show', [
            'entity' => $area,
            'collection' => $orders,
            'departments' => [],
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
        $departments = $this->em->getRepository(Department::class)
                                ->findBy([], ['name' => 'asc']);
        $users = $this->em->getRepository(User::class)
                                ->findBy([], ['email' => 'asc']);

        return view('areas.form', [
            'route' => route('areas.update', ['area' => $area->getId()]),
            'method' => 'PUT',
            'entity' => $area,
            'users' => $users,
            'departments' => $departments,
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AreaRequest $request, Area $area)
    {
        $this->hydrateData($area, $request->all());
        $this->em->flush();
        return redirect()->route('areas.show', ['area' => $area->getId()])
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

    /**
     * @param Area $entity
     * @param array $data
     *
     * @return void 
     */
    protected function hydrateData(Area $entity, array $data)
    {
        $entity->setType($data['type']);

        $entity->setLCode();
        if (isset($data['lcode'])) {
            $entity->setLCode($data['lcode']);
        }

        $entity->getUsers()->clear();
        if (isset($data['users']) && is_array($data['users'])) {
            $er = $this->em->getRepository(User::class);
            foreach ($data['users'] as $id) {
                $entity->addUser($er->find($id));
            }
        }

        if (null === ($e = $this->em->find(Department::class, $data['department']))) {
            throw new \RuntimeException("Department {$data['department']} not found"); 
        }
        $entity->setDepartment($e);
    }
}
