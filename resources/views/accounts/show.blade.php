@extends('new_layout')
@section('title')
    {{ __('Account :name', ['name' => $entity->getName()]) }} <small>({{$entity->getSerial()}})</small>
@endsection
@section('btn-toolbar')
    {{ Form::open([
        'route' => ['accounts.destroy', $entity->getId()], 
        'method' => 'delete',
    ]) }}
    @if ($entity->getSubaccounts()->count() === 1)
        <a href="{{ route('subaccounts.orders.create', ['subaccount' => $entity->getSubaccounts()->first()->getId()]) }}" class="btn btn-sm btn-outline-secondary">
            <span data-feather="file"></span> {{ __('New order') }} 
        </a>
        <a href="{{ route('subaccounts.assignments.create', ['subaccount' => $entity->getSubaccounts()->first()->getId()]) }}" class="btn btn-sm btn-outline-secondary">
            <span data-feather="dollar-sign"></span> {{ __('New assignment') }} 
        </a>
    @endif
    <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('accounts.edit', ['account' => $entity->getId()]) }}" class="btn btn-outline-secondary">
            <span data-feather="edit-2"></span>
        </a>
        {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit']) }}
    </div>
    {{ Form::close() }}
@endsection

@section('content')

@if ($entity->getSubaccounts()->count() > 1)
    @include ('accounts.show.many-areas', ['entity' => $entity])
@else
    @include ('accounts.show.one-area', ['entity' => $entity])
@endif

<ul class="nav nav-tabs justify-content-center mb-3">
  <li class="nav-item">
    <a class='nav-link {{request()->is("accounts/{$entity->getId()}")?" active":"" }}' href="{{ route('accounts.show', ['account' => $entity->getId()]) }}">{{__('Orders')}}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('accounts/*/movements')?' active':'' }}" href="{{ route('accounts.movements.index', ['account' => $entity->getId()]) }}">{{__('Movements')}}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('accounts/*/assignments')?' active':'' }}" href="{{ route('accounts.assignments.index', ['account' => $entity->getId()]) }}">{{__('Assignments')}}</a>
  </li>
</ul>

@yield('body', View::make('accounts.body', ['collection' => $collection, 'entity' => $entity]))
@endsection
