@extends('sj_layout')
@section('title')
    {{ __('User :email', ['email' => $entity->getEmail()]) }}
@endsection

@section('content')
<ul class="nav nav-tabs justify-content-center border-0">
  <li class="nav-item">
    <a class='nav-link {{request()->is("users/{$entity->getId()}")?" active":"" }}' href="{{ route('users.show', ['user' => $entity->getId()]) }}">
        <i class="bx bxs-credit-card"></i> {{ __('Accounts') }}
    </a>
  </li>
  <li class="nav-item">
    <a class='nav-link {{request()->is("users/{$entity->getId()}/orders*")?" active":"" }}' href="{{ route('users.orders.index', ['user' => $entity->getId()]) }}">
        <i class="bx bx-file"></i> {{ __('Orders') }}
    </a>
  </li>
  <li class="nav-item">
    <a class='nav-link {{request()->is("users/{$entity->getId()}/actions*")?" active":"" }}' href="{{ route('users.actions.index', ['user' => $entity->getId()]) }}">
        <i class="bx bx-pulse"></i> {{ __('Activity') }}
    </a>
  </li>
</ul>

<div class="bg-white border rounded rounded-5 px-2 mb-2">
    @yield('body', View::make('users.body', [
        'collection' => $collection, 
        'entity'     => $entity,
        'pagination' => true,
    ]))
</div>
@endsection

