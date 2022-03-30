@extends('new_layout')
@section('title')
    {{ __('Supplier :name', ['name' => $entity->getName()]) }}
@endsection
@section('btn-toolbar')
    {{ Form::open([
        'route' => ['suppliers.destroy', $entity->getId()], 
        'method' => 'delete',
    ]) }}
    <a href="{{ route('suppliers.contacts.create', ['supplier' => $entity->getId()]) }}" class='btn btn-sm btn-outline-secondary {{request()->is("suppliers/{$entity->getId()}/contacts/create") ? "active" : "" }}'>
        <span data-feather="user"></span> {{ __('New contact') }}
    </a>
    <a href="{{ route('suppliers.incidences.create', ['supplier' => $entity->getId()]) }}" class='btn btn-sm btn-outline-secondary {{request()->is("suppliers/{$entity->getId()}/incidences/create") ? "active" : "" }}'>
        <span data-feather="bell"></span> {{ __('New incidence') }}
    </a>
    <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('suppliers.edit', ['supplier' => $entity->getId()]) }}" class="btn btn-outline-secondary">
            <span data-feather="edit-2"></span>
        </a>
        {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit', 'disabled' => $entity->getProducts()->count() ? true : false]) }}
    </div>
    {{ Form::close() }}
@endsection
 
@section('content')
<div class="table-responsive">
  <table class="table table-hover table-sm align-middle">
        <thead>
        <tr>
            <th>NIF</th>
            <th>Zip</th>
            <th>Location</th>
            <th>Address</th>
            <th>Acceptable</th>
            <th>Recommended</th>
        </tr>
        <thead>
        <tbody>
        <tr>
            <td>{{ $entity->getNif() }}</td>
            <td>{{ $entity->getZip() }}</td>
            <td>{{ $entity->getCity() }}</td>
            <td>{{ $entity->getAddress() }}</td>
            <td>{{ $entity->getAcceptable() ? "Yes":"No" }}</td>
            <td>{{ $entity->getRecommendable() ? "Yes":"No" }}</td>
        </tr>
        </tbody>
    </table>
</div>
   
<ul class="nav nav-tabs justify-content-center">
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

<div class="pt-2">
    @yield('body', View::make('suppliers.contacts', ['entity' => $entity]))
</div>
@endsection
