@extends('new_layout')
@section('title')
    {{ __('Area :name', ['name' => $entity->getName()]) }}
@endsection
@section('content')
<ul class="nav nav-tabs justify-content-center mb-3">
  <li class="nav-item">
    <a class='nav-link {{request()->is("areas/{$entity->getId()}")?" active":"" }}' href="{{ route('areas.show', ['area' => $entity->getId()]) }}">{{__('Accounts')}}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('areas/*/assignments')?' active':'' }}" href="">{{__('Orders')}}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('areas/*/movements')?' active':'' }}" href="">{{__('Movements')}}</a>
  </li>
</ul>

@yield('body', View::make('areas.accounts', [
    'collection' => $entity->getSubaccounts(), 
    'entity'     => $entity
]))
@endsection

