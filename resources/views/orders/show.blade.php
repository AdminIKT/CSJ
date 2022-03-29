@extends('new_layout')
@section('title')
    {{ __('Order nº :number', ['number' => $entity->getSequence()]) }}
@endsection
@section('btn-toolbar')
    {{ Form::open([
        'route' => ['orders.destroy', $entity->getId()], 
        'method' => 'delete',
    ]) }}
    <a href="{{ route('orders.invoices.create', ['order' => $entity->getId()]) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
        <span data-feather="file-text"></span> {{ __('pdf') }} 
    </a>
    <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('orders.edit', ['order' => $entity->getId()]) }}" class="btn btn-outline-secondary">
            <span data-feather="edit-2"></span>
        </a>
        {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit', 'disabled' => $entity->isStatus(\App\Entities\Order::STATUS_CREATED) ? false : true]) }}
    </div>
    {{ Form::close() }}
@endsection
 
@section('content')
<div class="table-responsive">
  <table class="table table-hover table-sm align-middle">
        <thead>
        <tr>
            <th>{{ __('Area') }}</th>
            <th>{{ __('Estimated') }}</th>
            <th>{{ __('Invoice') }}</th>
            <th>{{ __('Status') }}</th>
            <th>{{ __('Credit') }}</th>
            <th>{{ __('Detail') }}</th>
            <th>{{ __('Created') }}</th>
        </tr>
        <thead>
        <tbody>
        <tr>
            <td><a href="{{ route('areas.show', ['area' => $entity->getArea()->getId()]) }}">{{ $entity->getArea() }}</td>
            <td>{{ $entity->getEstimatedCredit() }}€</td>
            <td>TODO</td>
            <td>{{ $entity->getStatusName() }}</td>
            <td>@if ($entity->getCredit()) {{ $entity->getCredit() }}€ @endif</td>
            <td>{{ $entity->getDetail() }}</td>
            <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
        </tr>
        </tbody>
  </table>
  <h4>{{ __('elementos') }}</h4>
  <table class="table table-hover table-sm align-middle">
        <thead>
        <tr>
            <th>{{ __('proveedor') }}</th>
            <th>{{ __('Detail') }}</th>
            <th>{{ __('Credit') }}</th>
            <th>{{ __('Actions') }}</th>
        </tr>
        <thead>
        <tbody>
        @foreach ($entity->getProducts() as $product)
        <tr>
            <td><a href="{{ route('suppliers.show', ['supplier' => $product->getSupplier()->getId()]) }}">{{ $product->getSupplier()->getName() }}</td>
            <td>{{ $product->getDetail() }}</td>
            <td>{{ $product->getCredit() }}€</td>
            <td>
                {{ Form::open([
                    'route' => ['orders.products.destroy', $entity->getId(), $product->getId()], 
                    'method' => 'delete',
                ]) }}
                <a href="{{ route('suppliers.incidences.create', ['supplier' => $product->getSupplier()->getId()]) }}" class="btn btn-sm btn-outline-secondary">
                    <span data-feather="plus"></span> {{ __('New incidence') }}
                </a>
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
    <a class='nav-link {{request()->is("orders/{$entity->getId()}", "orders/{$entity->getId()}/movements*")?" active":"" }}' href="">{{ __('Movements') }}</a>
  </li>
</ul>

<div class="pt-2">
    @yield('body', View::make('movements.shared.table', ['collection' => $entity->getMovements()]))
</div>
@endsection
