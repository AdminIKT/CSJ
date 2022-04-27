@extends('new_layout')
@section('title')
    {{ __('Account :name', ['name' => $entity->getName()]) }} <small>({{$entity->getTypeName()}})</small>
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
<div class="table-responsive">
  <table class="table table-hover table-sm align-middle">
        <thead>
        <tr>
            <th>{{ __('acronimo') }}</th>
            <th>{{ __('Area') }}</th>
            <th>{{ __('Users') }}</th>
            <th>{{ __('Real credit') }}</th>
            <th>{{ __('Compromised credit') }}</th>
            <th>{{ __('Available credit') }}</th>
            <th>{{ __('Created') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $entity->getSerial() }}</td>
            <td>{{ implode(", ", $entity->getAreas()->map(function ($e) { return $e->getName(); })->toArray()) }}</td>
            <td>{{ implode(", ", $entity->getUsers()->map(function ($e) { return $e->getName(); })->toArray()) }}</td>
            <td>{{ number_format($entity->getCredit(), 2, ",", ".") }}€</td>
            <td>{{ number_format($entity->getCompromisedCredit(), 2, ",", ".") }}€</td>
            <td>{{ number_format($entity->getAvailableCredit(), 2, ",", ".") }}€</td>
            <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
        </tr>
        @if ($entity->getSubaccounts()->count() > 1)
            @foreach ($entity->getSubaccounts() as $subaccount)
                <tr>
                    <td></td>
                    <td>{{ $subaccount->getArea() }}</td>
                    <td></td>
                    <td>{{ number_format($subaccount->getCredit(), 2, ",", ".") }}€</td>
                    <td>{{ number_format($subaccount->getCompromisedCredit(), 2, ",", ".") }}€</td>
                    <td>{{ number_format($subaccount->getAvailableCredit(), 2, ",", ".") }}€</td>
                    <td>
                    <a href="{{ route('subaccounts.orders.create', ['subaccount' => $subaccount->getId()]) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('New order') }}">
                            <span data-feather="file"></span>
                        </a>
                        <a href="{{ route('subaccounts.assignments.create', ['subaccount' => $subaccount->getId()]) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('New assignment') }}">
                            <span data-feather="dollar-sign"></span>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
  </table>
</div>

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
