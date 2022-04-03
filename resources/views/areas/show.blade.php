@extends('new_layout')
@section('title')
    {{ __('Area :name', ['name' => $entity->getName()]) }}
@endsection
@section('btn-toolbar')
    {{ Form::open([
        'route' => ['areas.destroy', $entity->getId()], 
        'method' => 'delete',
    ]) }}
    <a href="{{ route('areas.orders.create', ['area' => $entity->getId()]) }}" class="btn btn-sm btn-outline-secondary">
        <span data-feather="file"></span> {{ __('New order') }} 
    </a>
    <a href="{{ route('assignments.create', ['area' => $entity->getId(), 'destination' => route('areas.assignments.index', ['area' => $entity->getId()])]) }}" class="btn btn-sm btn-outline-secondary">
        <span data-feather="dollar-sign"></span> {{ __('New assignment') }} 
    </a>
    <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('areas.edit', ['area' => $entity->getId()]) }}" class="btn btn-outline-secondary">
            <span data-feather="edit-2"></span>
        </a>
        {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit']) }}
    </div>
    {{ Form::close() }}
@endsection

@section('content')
<div class="table-responsive">
  <table class="table table-hover table-sm align-middle">
        <thead>
        <tr>
            <th>{{ __('acronimo') }}</th>
            <th>{{ __('tipo') }}</th>
            <th>{{ __('departamentos') }}</th>
            <th>{{ __('Accounts') }}</th>
            <th>{{ __('Real credit') }}</th>
            <th>{{ __('Compromised credit') }}</th>
            <th>{{ __('Available credit') }}</th>
            <th>{{ __('Created') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $entity->getSerial() }}</td>
            <td>{{ $entity->getTypeName() }}</td>
            <td>{{ implode(", ", $entity->getDepartments()->map(function ($e) { return $e->getName(); })->toArray()) }}</td>
            <td>{{ implode(", ", $entity->getUsers()->map(function ($e) { return $e->getEmail(); })->toArray()) }}</td>
            <td>{{ number_format($entity->getCredit(), 2, ",", ".") }}€</td>
            <td>{{ number_format($entity->getCompromisedCredit(), 2, ",", ".") }}€</td>
            <td>{{ number_format($entity->getAvailableCredit(), 2, ",", ".") }}€</td>
            <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
        </tr>
        </tbody>
  </table>
</div>

<ul class="nav nav-tabs justify-content-center mb-3">
  <li class="nav-item">
    <a class='nav-link {{request()->is("areas/{$entity->getId()}")?" active":"" }}' href="{{ route('areas.show', ['area' => $entity->getId()]) }}">{{__('Orders')}}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('areas/*/movements')?' active':'' }}" href="{{ route('areas.movements.index', ['area' => $entity->getId()]) }}">{{__('Movements')}}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('areas/*/assignments')?' active':'' }}" href="{{ route('areas.assignments.index', ['area' => $entity->getId()]) }}">{{__('Assignments')}}</a>
  </li>
</ul>

@yield('body', View::make('areas.body', ['collection' => $collection, 'entity' => $entity]))
@endsection
