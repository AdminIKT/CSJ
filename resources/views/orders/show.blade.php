@extends('new_layout')
@section('title')
    {{ __('Order nº :number', ['number' => $entity->getSequence()]) }}
@endsection
@section('btn-toolbar')
    {{ Form::open([
        'route' => ['orders.destroy', $entity->getId()], 
        'method' => 'delete',
    ]) }}
    <a href="{{ route('suppliers.incidences.create', ['supplier' => $entity->getSupplier()->getId(), 'order' => $entity->getId(), 'destination' => route('orders.incidences.index', ['order' => $entity->getId()])]) }}" class="btn btn-sm btn-outline-secondary">
        <span data-feather="bell"></span> {{ __('New incidence') }}
    </a>
    <a href="{{ route('orders.invoices.create', ['order' => $entity->getId()]) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
        <span data-feather="file-text"></span> {{ __('pdf') }} 
    </a>
    <div class="btn-group">
        <button id="emailBtn" class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span data-feather="mail"></span> Email 
        </button>
        <ul class="dropdown-menu" aria-labelledby="emailBtn">
            @foreach ($entity->getSupplier()->getContacts() as $contact)
                <li><a class="dropdown-item {{ $contact->getEmail() ? '' : 'disabled' }}" href="">{{ $contact->getName() }} ({{ $contact->getEmail() ?? 'Empty' }})</a></li>
            @endforeach
            @if ($entity->getSupplier()->getContacts()->count())
                <li><hr class="dropdown-divider"></li>
            @endif
            <li>
                <a class="dropdown-item" href="{{ route('suppliers.contacts.create', ['supplier' => $entity->getSupplier()->getId(), 'destination' => route('orders.show', ['order' => $entity->getId()])]) }}">{{ __('New contact') }}</a>
            </li>
        </ul>
    </div>
    <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('orders.edit', ['order' => $entity->getId()]) }}" class="btn btn-outline-secondary">
            <span data-feather="edit-2"></span>
        </a>
        {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit', 'disabled' => $entity->isPending() ? false : true]) }}
    </div>
    {{ Form::close() }}
@endsection
 
@section('content')
<div class="table-responsive">
  <table class="table table-hover table-sm align-middle">
        <thead>
        <tr>
            <th>{{ __('Account') }}</th>
            <th>{{ __('Area') }}</th>
            <th>{{ __('Supplier') }}</th>
            <th>{{ __('Estimated') }}</th>
            <th>{{ __('Presupuesto') }}</th>
            <th>{{ __('Invoice') }}</th>
            <th>{{ __('Status') }}</th>
            <th>{{ __('Credit') }}</th>
            <th>{{ __('Receive in') }}</th>
            <th>{{ __('Detail') }}</th>
            <th>{{ __('User') }}</th>
            <th>{{ __('Created') }}</th>
        </tr>
        <thead>
        <tbody>
        <tr>
            <td><a href="{{ route('accounts.show', ['account' => $entity->getAccount()->getId()]) }}">{{ $entity->getAccount()->getSerial() }}</td>
            <td><a href="{{ route('areas.show', ['area' => $entity->getArea()->getId()]) }}">{{ $entity->getArea() }}</td>
            <td><a href="{{ route('suppliers.show', ['supplier' => $entity->getSupplier()->getId()]) }}">{{ $entity->getSupplier()->getName() }}</a></td>
            <td>{{ number_format($entity->getEstimatedCredit(), 2, ",", ".") }}€</td>
            <td>@if ($entity->getEstimated())<a href='{{ asset("storage/{$entity->getEstimated()}") }}' target="_blank">{{ $entity->getEstimated() }}</a>@else-@endif</td>
            <td>{{ $entity->getInvoice() }}</td>
            <td>{{ $entity->getStatusName() }}</td>
            <td>@if ($entity->getCredit()) {{ number_format($entity->getCredit(), 2, ",", ".") }}€ @endif</td>
            <td>{{ $entity->getReceiveInName() }}</td>
            <td>{{ $entity->getDetail() }}</td>
            <td>{{ $entity->getUser()->getName() }}</td>
            <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
        </tr>
        </tbody>
  </table>
  <h6>{{ __('elementos') }}</h6>
  <table class="table table-hover table-sm align-middle">
        <thead>
        <tr>
            <th>{{ __('Detail') }}</th>
            <th>{{ __('cantidad') }}</th>
            <th>{{ __('Actions') }}</th>
        </tr>
        <thead>
        <tbody>
        @foreach ($entity->getProducts() as $product)
        <tr>
            <td>{{ $product->getDetail() }}</td>
            <td>{{ $product->getUnits() }}</td>
            <td>
                {{ Form::open([
                    'route' => ['orders.products.destroy', $entity->getId(), $product->getId()], 
                    'method' => 'delete',
                ]) }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="" class='btn btn-sm btn-outline-secondary disabled'>
                        <span data-feather="edit-2"></span>
                   </a>
                   {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit', 'disabled' => true]) }}
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
   
<ul class="nav nav-tabs justify-content-center">
  <li class="nav-item">
    <a class='nav-link {{request()->is("orders/{$entity->getId()}", "orders/{$entity->getId()}/invoiceCharges*")?" active":"" }}' href="{{ route('orders.show', ['order' => $entity->getId()]) }}">{{ __('Movements') }}</a>
  </li>
  <li class="nav-item">
    <a class='nav-link {{request()->is("orders/{$entity->getId()}/incidences*")?" active":"" }}' href="{{ route('orders.incidences.index', ['order' => $entity->getId()]) }}">{{ __('Incidences') }}</a>
  </li>
</ul>

<div class="pt-2">
    @yield('body', View::make('movements.shared.table', ['collection' => $collection, 'exclude' => ['orders', 'accounts', 'areas', 'suppliers']]))
</div>
@endsection
