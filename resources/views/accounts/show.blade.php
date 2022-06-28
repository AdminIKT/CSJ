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
        @can('view', $entity)
        <a href="{{ route('subaccounts.orders.create', ['subaccount' => $entity->getSubaccounts()->first()->getId()]) }}" class="btn btn-sm btn-outline-secondary">
            <span data-feather="file"></span> {{ __('New order') }} 
        </a>
        @endcan
        @can('update', $entity)
        <div class="btn-group">
            <button id="movementBtn" class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span data-feather="shopping-cart"></span> {{ __('New movement') }} 
            </button>
            <ul class="dropdown-menu" aria-labelledby="movementBtn">
                <li>
                    <a href="{{ route('subaccounts.assignments.create', ['subaccount' => $entity->getSubaccounts()->first()->getId()]) }}" class="dropdown-item">+<span data-feather="dollar-sign"></span> {{ __('New assignment') }}</a>
                </li>
                <li>
                    <a href="{{ route('subaccounts.charges.create', ['subaccount' => $entity->getSubaccounts()->first()->getId()]) }}" class="dropdown-item">-<span data-feather="dollar-sign"></span> {{ __('New charge') }}</a>
                </li>
            </ul>
        </div>
        @endcan
    @endif
    <div class="btn-group btn-group-sm" role="group">
        @can('update', $entity)
        <a href="{{ route('accounts.edit', ['account' => $entity->getId()]) }}" class="btn btn-outline-secondary">
            <span data-feather="edit-2"></span>
        </a>
        @endcan
        @can('delete', $entity)
        {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit']) }}
        @endcan
    </div>
    {{ Form::close() }}
@endsection

@section('content')
<p class="small"><strong>{{ __('Users') }}:</strong> {{ implode(", ", $entity->getUsers()->map(function ($e) { return $e->getName(); })->toArray()) }}</p>

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
</ul>

@yield('body', View::make('accounts.body', ['collection' => $collection, 'entity' => $entity, 'perPage' => $perPage]))
@endsection
