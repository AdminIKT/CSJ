@extends('sj_layout')
@section('title')
    {{ __('Supplier :name', ['name' => $entity->getName()]) }}
@endsection
@section('btn-toolbar')
    {{ Form::open([
        'route' => ['suppliers.destroy', $entity->getId()], 
        'method' => 'delete',
        'class' => ' m-1 ms-0'
    ]) }}
    <a href="{{ route('suppliers.contacts.create', ['supplier' => $entity->getId()]) }}" class='btn btn-sm btn-outline-secondary {{request()->is("suppliers/{$entity->getId()}/contacts/create") ? "active" : "" }} m-1'>
        <span data-feather="user"></span> {{ __('New contact') }}
    </a>
    <a href="{{ route('suppliers.incidences.create', ['supplier' => $entity->getId()]) }}" class='btn btn-sm btn-outline-secondary {{request()->is("suppliers/{$entity->getId()}/incidences/create") ? "active" : "" }} m-1'>
        <span data-feather="bell"></span> {{ __('New incidence') }}
    </a>
    <div class="btn-group btn-group-sm m-1" role="group">
        <a href="{{ route('suppliers.edit', ['supplier' => $entity->getId()]) }}" class="btn btn-outline-secondary">
            <span data-feather="edit-2"></span>
        </a>
        {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit', 'disabled' => $entity->getOrders()->count() ? true : false]) }}
    </div>
    {{ Form::close() }}
@endsection
 
@section('content')
<div class="table-responsive">
    <table class="table table-sm align-middle table-bordered border-white">
        <tr>
            <th>{{ __('NIF') }}</th>
            <th>{{ __('Zip') }}</th>
            <th>{{ __('Location') }}</th>
            <th>{{ __('Address') }}</th>
            <th>{{ __('Acceptable') }}</th>
            <th>{{ __('Recommendable') }}</th>
        </tr>
        <tr>
            <td class="table-active">{{ $entity->getNif() }}</td>
            <td class="table-active">{{ $entity->getZip() }}</td>
            <td class="table-active">{{ $entity->getCity() }}@if ($entity->getRegion()) ({{ $entity->getRegion() }}) @endif</td>
            <td class="table-active">{{ $entity->getAddress() }}</td>
            <td>{{ $entity->getAcceptable() ? __('Yes'):__('No') }}</td>
            <td>{{ $entity->getRecommendable() ? __('Yes'):__('No') }}</td>
        </tr>
    </table>
</div>
   
<ul class="nav nav-tabs justify-content-center border-0">
  <li class="nav-item">
    <a class='nav-link {{request()->is("suppliers/{$entity->getId()}", "suppliers/{$entity->getId()}/contacts*")?" active":"" }}' href="{{ route('suppliers.show', ['supplier' => $entity->getId()]) }}">{{ __('Contacts') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('suppliers/*/incidences*')?' active':'' }}" href="{{ route('suppliers.incidences.index', ['supplier' => $entity->getId()]) }}" tabindex="-1" aria-disabled="true">{{ __('Incidences') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('suppliers/*/orders*')?' active':'' }}" href="{{ route('suppliers.orders.index', ['supplier' => $entity->getId()]) }}" tabindex="-1" aria-disabled="true">{{ __('Orders') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('suppliers/*/movements*')?' active':'' }}" href="{{ route('suppliers.movements.index', ['supplier' => $entity->getId()]) }}" tabindex="-1" aria-disabled="true">{{ __('Movements') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('suppliers/*/invoiceds*')?' active':'' }}" href="{{ route('suppliers.invoiceds.index', ['supplier' => $entity->getId()]) }}" tabindex="-1" aria-disabled="true">{{ __('Invoiced') }}</a>
  </li>
</ul>

<div class="bg-white border rounded rounded-5 px-2 mb-2">
    @yield('body', View::make('suppliers.contacts', [
        'entity' => $entity
    ]))
</div>
@endsection
