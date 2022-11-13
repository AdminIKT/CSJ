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
<div class="row">
    <div class="table-responsive col-sm-12 col-md-6">
        <table class="table table-sm align-middle table-bordered border-white">
            <tr>
                <th>{{ __('NIF') }}</th>
                <th>{{ __('Zip') }}</th>
                <th>{{ __('Location') }}</th>
                <th>{{ __('Address') }}</th>
            </tr>
            <tr>
                <td class="table-secondary">{{ $entity->getNif() }}</td>
                <td class="table-secondary">{{ $entity->getZip() }}</td>
                <td class="table-secondary">{{ $entity->getCity() }}
                    @if ($entity->getRegion()) 
                        <span class="small text-muted">({{ $entity->getRegion() }})</span>
                    @endif
                </td>
                <td class="table-secondary">{{ $entity->getAddress() }}</td>
            </tr>
            <tr>
                <td rowspan="2" colspan="2" class="text-center">
                    <span class="badge {{ $entity->getStatusColor() }}">{{ $entity->getStatusName() }}</span>
                </td>
                <th>{{ __('Orders') }}</th>
                <th>{{ __('Incidences') }}</th>
            </tr>
            <tr>
                <td>{{ $entity->getOrderCount() }}</td>
                <td>{{ $entity->getIncidenceCount() }}</td>
            </tr>
        </table>
    </div>
    <div class="table-responsive col-sm-12 col-md-6">
        <table class="table table-sm align-middle table-bordered border-white">
            <tr>
                <th>{{ __('Year') }}</th>
                <th>{{ __('Predicted') }}</th>
                <th>{{ __('Invoiced') }}</th>
            </tr>
            @foreach ($entity->getInvoiced() as $inv)
            <tr>
                <td class="border">{{ $inv->getYear() }}</td>
                <td class="table-secondary">{{ number_format($inv->getEstimated(), 2, ",", ".")}} €</td>
                <td class="table-secondary">{{ number_format($inv->getCredit(), 2, ",", ".")}} €</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
   
<ul class="nav nav-tabs justify-content-center border-0">
  <li class="nav-item">
    <a class='nav-link {{request()->is("suppliers/{$entity->getId()}", "suppliers/{$entity->getId()}/contacts*")?" active":"" }}' href="{{ route('suppliers.show', ['supplier' => $entity->getId()]) }}">
        <span data-feather="user"></span> {{ __('Contacts') }}
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('suppliers/*/incidences*')?' active':'' }}" href="{{ route('suppliers.incidences.index', ['supplier' => $entity->getId()]) }}" tabindex="-1" aria-disabled="true">
        <span data-feather="bell"></span> {{ __('Incidences') }}
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('suppliers/*/orders*')?' active':'' }}" href="{{ route('suppliers.orders.index', ['supplier' => $entity->getId()]) }}" tabindex="-1" aria-disabled="true">
        <span data-feather="file"></span> {{ __('Orders') }}
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('suppliers/*/movements*')?' active':'' }}" href="{{ route('suppliers.movements.index', ['supplier' => $entity->getId()]) }}" tabindex="-1" aria-disabled="true">
        <span data-feather="dollar-sign"></span> {{ __('Movements') }}
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('suppliers/*/invoiceds*')?' active':'' }}" href="{{ route('suppliers.invoiceds.index', ['supplier' => $entity->getId()]) }}" tabindex="-1" aria-disabled="true">
        <span data-feather="award"></span> {{ __('Invoiced') }}
    </a>
  </li>
</ul>

<div class="bg-white border rounded rounded-5 px-2 mb-2">
    @yield('body', View::make('suppliers.contacts', [
        'entity' => $entity
    ]))
</div>
@endsection
