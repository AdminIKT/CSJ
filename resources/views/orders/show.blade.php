@extends('sj_layout')
@section('title')
    {{ __('Order nº :number', ['number' => $entity->getSequence()]) }}
@endsection
@section('btn-toolbar')

    {{ Form::open([
        'route' => ['orders.status', $entity->getId()], 
        'method' => 'post',
        'class' => ' m-1 ms-0'
    ]) }}
        <div class="input-group input-group-sm">
            <span class="input-group-text">
                <i class="badge {!! $entity->getStatusColor() !!}">
                </i>
            </span>
            <select id="statusSel" name="status" class="form-select" onchange="enableBtn(this)">
                <option selected="selected" disabled="true">{{ $entity->getStatusName() }}</option>
                @can('order-status-received', $entity)
                    <option value="{{ App\Entities\Order::STATUS_RECEIVED }}">{{ \App\Entities\Order::statusName(\App\Entities\Order::STATUS_RECEIVED) }}</option>
                @endcan
                @can('order-status-checked-not-agreed', $entity)
                    <option value="{{ App\Entities\Order::STATUS_CHECKED_NOT_AGREED }}">{{ \App\Entities\Order::statusName(\App\Entities\Order::STATUS_CHECKED_NOT_AGREED) }}</option>
                @endcan
                @can('order-status-checked-partial-agreed', $entity)
                    <option value="{{ App\Entities\Order::STATUS_CHECKED_PARTIAL_AGREED }}">{{ \App\Entities\Order::statusName(\App\Entities\Order::STATUS_CHECKED_PARTIAL_AGREED) }}</option>
                @endcan
                @can('order-status-checked-agreed', $entity)
                    <option value="{{ App\Entities\Order::STATUS_CHECKED_AGREED }}">{{ \App\Entities\Order::statusName(\App\Entities\Order::STATUS_CHECKED_AGREED) }}</option>
                @endcan
                @can('order-status-checked-invoiced', $entity)
                    <option value="{{ App\Entities\Order::STATUS_CHECKED_INVOICED }}">{{ \App\Entities\Order::statusName(\App\Entities\Order::STATUS_CHECKED_INVOICED) }}</option>
                @endcan
                @can('order-status-paid', $entity)
                    <option value="{{ App\Entities\Order::STATUS_PAID }}">{{ \App\Entities\Order::statusName(\App\Entities\Order::STATUS_PAID) }}</option>
                @endcan
            </select>
            <button id="statusBtn" class="btn btn-outline-secondary disabled" type="submit">{{ __('guardar') }}</button>
        </div>
    {{ Form::close() }}

    <a class="btn btn-sm btn-outline-secondary m-1" href="{{ route('orders.invoices.create', ['order' => $entity->getId()]) }}" target="_blank">
        <span data-feather="file-text"></span> {{ __('pdf') }} 
    </a>

    <div class="btn-group m-1">
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

    <a class="btn btn-sm btn-outline-secondary m-1" href="{{ route('suppliers.incidences.create', ['supplier' => $entity->getSupplier()->getId(), 'order' => $entity->getId(), 'destination' => route('orders.incidences.index', ['order' => $entity->getId()])]) }}">
        <span data-feather="bell"></span> {{ __('New incidence') }}
    </a>

    {{ Form::open([
        'route' => ['orders.destroy', $entity->getId()], 
        'method' => 'delete',
        'class' => 'm-1 me-0'
    ]) }}
    <div class="btn-group btn-group-sm" role="group">
        @can('update', $entity)
        <a href="{{ route('orders.edit', ['order' => $entity->getId()]) }}" class="btn btn-outline-secondary">
            <span data-feather="edit-2"></span>
        </a>
        @endcan
        @can('delete', $entity)
        {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit', 'disabled' => $entity->isPending() ? false : true]) }}
        @endcan
    </div>
    {{ Form::close() }}
@endsection
 
@section('content')
<div class="d-flex flex-wrap">
    <div class="table-responsive col-sm me-sm-1 w-100" style="">
        <table class="table table-sm align-middle table-bordered border-white">
            <tr>
                <th>{{ __('Account') }}</th>
                <th>{{ __('Area') }}</th>
            </tr>
            <tr class="table-active">
                <td>
                    <a href="{{ route('accounts.show', ['account' => $entity->getAccount()->getId()]) }}" title="{{ $entity->getAccount()->getName() }}">{{ $entity->getAccount()->getSerial() }}</a>
                    <small class="text-muted">{{ $entity->getAccount()->getName() }}</small>
                </td>
                <td><a href="{{ route('areas.show', ['area' => $entity->getArea()->getId()]) }}">{{ $entity->getArea() }}</td>
            </tr>
            <tr>
                <th colspan="2">{{ __('Supplier') }}</th>
            </tr>
            <tr class="table-active">
                <td colspan="2"><a href="{{ route('suppliers.show', ['supplier' => $entity->getSupplier()->getId()]) }}">{{ $entity->getSupplier()->getName() }}</a></td>
            </tr>
            <tr>
                <th>{{ __('Presupuesto') }}</th>
                <th>{{ __('Estimated') }}</th>
            </tr>
            <tr class="table-active">
                <td>@if ($entity->getEstimated())<a href='{{ asset("storage/{$entity->getEstimated()}") }}' target="_blank">{{ $entity->getEstimated() }}</a>@else-@endif</td>
                 <td>{{ $entity->getEstimatedCredit() ? number_format($entity->getEstimatedCredit(), 2, ",", ".").'€' : '-'}}</td>
            </tr>
            <tr>
                <th>{{ __('Invoice') }}</th>
                <th>{{ __('Credit') }}</th>
            </tr>
            <tr class="table-active">
                <td>{{ $entity->getInvoice() ?? "-" }}</td>
                <td>{{ $entity->getCredit() ? number_format($entity->getCredit(), 2, ",", ".").'€' : '-'}}</td>
            </tr>
        </table>
    </div>
    <div class="table-responsive col-sm ms-sm-1 w-100" style="">
        <table class="table table-sm align-middle table-bordered border-white">
            <tr>
                <th>{{ __('User') }}
                    <small class="small text-muted">({{ __('Created') }})</small>
                </th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Receive in') }}</th>
            </tr>
            <tr class="table-active">
                <td>
                    {{ $entity->getUser()->getName() }}
                    <span class="small text-muted">{{ Carbon\Carbon::parse($entity->getCreated())->diffForHumans() }}</span>
                </td>
                <td>{{ $entity->getDate()->format("d/m/Y H:i") }}</td>
                <td>{{ $entity->getReceiveInName() }}</td>
            </tr>
            @if (count($entity->getProducts())) 
                <tr>
                    <th>{{ __('elementos') }}</th>
                    <th colspan="2">{{ __('cantidad') }}</th>
                </tr>
                @foreach ($entity->getProducts() as $product)
                <tr class="table-active">
                    <td>{{ $product->getDetail() }}</td>
                    <td>{{ $product->getUnits() }}</td>
                    <td class="text-center">
                        {{ Form::open([
                            'route' => ['orders.products.destroy', $entity->getId(), $product->getId()], 
                            'method' => 'delete',
                        ]) }}
                        <div class="btn-group btn-group-sm">
                            <a href="" class='btn disabled'>
                                <span data-feather="edit-2"></span>
                           </a>
                           {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn', 'type' => 'submit', 'disabled' => true]) }}
                        </div>
                    </td>
                </tr>
                @endforeach
            @endif
        </table>

        <p><b>{{ __('Detail') }}:</b> {{ $entity->getDetail() }}</p>
    </div>   
</div>

<!--
<div class="table-responsive">
  <table class="table table-hover table-sm align-middle table-bordered">
        <thead>
        <tr>
            <th>{{ __('Account') }}</th>
            <th>{{ __('Area') }}</th>
            <th>{{ __('Supplier') }}</th>
            <th>{{ __('Estimated') }}</th>
            <th>{{ __('Presupuesto') }}</th>
            <th>{{ __('Invoice') }}</th>
            <th>{{ __('Credit') }}</th>
            <th>{{ __('Receive in') }}</th>
            <th>{{ __('Detail') }}</th>
            <th>{{ __('User') }}</th>
            <th>{{ __('Created') }}</th>
        </tr>
        <thead>
        <tbody>
        <tr class="table-active">
            <td><a href="{{ route('accounts.show', ['account' => $entity->getAccount()->getId()]) }}">{{ $entity->getAccount()->getSerial() }}</td>
            <td><a href="{{ route('areas.show', ['area' => $entity->getArea()->getId()]) }}">{{ $entity->getArea() }}</td>
            <td><a href="{{ route('suppliers.show', ['supplier' => $entity->getSupplier()->getId()]) }}">{{ $entity->getSupplier()->getName() }}</a></td>
            <td>{{ number_format($entity->getEstimatedCredit(), 2, ",", ".") }}€</td>
            <td>@if ($entity->getEstimated())<a href='{{ asset("storage/{$entity->getEstimated()}") }}' target="_blank">{{ $entity->getEstimated() }}</a>@else-@endif</td>
            <td>{{ $entity->getInvoice() }}</td>
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
-->
<ul class="nav nav-tabs justify-content-center border-0">
  <li class="nav-item">
    <a class='nav-link {{request()->is("orders/{$entity->getId()}", "orders/{$entity->getId()}/invoiceCharges*")?" active":"" }}' href="{{ route('orders.show', ['order' => $entity->getId()]) }}">
    <span data-feather="dollar-sign"></span> {{ __('Movements') }}
    </a>
  </li>
  <li class="nav-item">
    <a class='nav-link {{request()->is("orders/{$entity->getId()}/incidences*")?" active":"" }}' href="{{ route('orders.incidences.index', ['order' => $entity->getId()]) }}">
    <span data-feather="shopping-bag"></span> {{ __('Incidences') }}
    </a>
  </li>
  <li class="nav-item">
    <a class='nav-link {{request()->is("orders/{$entity->getId()}/actions*")?" active":"" }}' href="{{ route('orders.actions.index', ['order' => $entity->getId()]) }}">
    <span data-feather="activity"></span> {{ __('Activity') }}
    </a>
  </li>
</ul>

<div class="bg-white border rounded rounded-5 px-2 mb-2">
    @yield('body', View::make('movements.shared.table', [
        'collection' => $collection, 
        'exclude' => ['orders', 'accounts', 'areas', 'suppliers'],
        'pagination' => true,
    ]))
</div>
@endsection

@section('scripts')
    <script>
        function enableBtn(input) {
            var value = $(input).val();
            var btn   = $("#statusBtn");
            if (value && btn.hasClass("disabled")) 
                $("#statusBtn").removeClass("disabled");
            else if (!btn.hasClass("disabled"))
                $("statusBtn").addClass("disabled");
        }
        $(document).ready(function() {
            enableBtn("#statusSel");
        });
    </script>
@endsection
