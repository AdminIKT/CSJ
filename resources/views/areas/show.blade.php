@extends('sj_layout')
@section('title')
    {{ __('Area :name', ['name' => $entity->getName()]) }}
@endsection
@section('content')
<ul class="nav nav-tabs justify-content-center border-0">
  <li class="nav-item">
    <a class='nav-link {{request()->is("areas/{$entity->getId()}")?" active":"" }}' href="{{ route('areas.show', ['area' => $entity->getId()]) }}">
        <span data-feather="credit-card"></span> {{__('Accounts')}}
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('areas/*/orders')?' active':'' }}" href="{{ route('areas.orders.index', ['area' => $entity->getId()]) }}  ">
        <span data-feather="file"></span> {{__('Orders')}}
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('areas/*/movements')?' active':'' }}" href="{{ route('areas.movements.index', ['area' => $entity->getId()]) }}">
        <span data-feather="dollar-sign"></span> {{__('Movements')}}
    </a>
  </li>
</ul>

<div class="bg-white border rounded rounded-5 px-2 mb-2">
    @yield('body', View::make('areas.body', [
        'collection' => $collection, 
        'entity'     => $entity,
        'pagination' => true,
    ]))
</div>
@endsection

