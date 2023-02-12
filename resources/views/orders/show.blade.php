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
                <i class="cbg {!! $entity->getStatusColor() !!}"></i>
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
                @can('order-status-cancelled', $entity)
                    <option value="{{ App\Entities\Order::STATUS_CANCELLED }}">{{ \App\Entities\Order::statusName(\App\Entities\Order::STATUS_CANCELLED) }}</option>
                @endcan
                <!--
                @can('order-status-paid', $entity)
                    <option value="{{ App\Entities\Order::STATUS_PAID }}">{{ \App\Entities\Order::statusName(\App\Entities\Order::STATUS_PAID) }}</option>
                @endcan
                -->
            </select>
            <button id="statusBtn" class="btn btn-outline-secondary disabled" type="submit">{{ __('guardar') }}</button>
        </div>
    {{ Form::close() }}

    <a class="btn btn-sm btn-outline-secondary m-1" 
        title="{{ __('Pdf') }}"
        href="{{ route('orders.invoices.create', ['order' => $entity->getId()]) }}" 
        target="_blank">
        <span class="bx bx-xs bxs-file-pdf bx-tada-hover"></span>
    </a>

   <!-- <div class="btn-group m-1">
        <button id="emailBtn" class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('Email') }}">
            <span class="bx bx-xs bxs-envelope bx-tada-hover"></span> 
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
    </div>-->

    <a class="btn btn-sm btn-outline-secondary m-1" 
        href="{{ route('suppliers.incidences.create', ['supplier' => $entity->getSupplier()->getId(), 'order' => $entity->getId(), 'destination' => route('orders.incidences.index', ['order' => $entity->getId()])]) }}"
        title="{{ __('New incidence') }}">
        <span class="bx bx-xs bxs-bell bx-tada-hover"></span> {{ __('New incidence') }}
    </a>

    {{ Form::open([
        'route' => ['orders.destroy', $entity->getId()], 
        'method' => 'delete',
        'class' => 'm-1 me-0'
    ]) }}
    <div class="btn-group btn-group-sm" role="group">
        @can('viewany', \App\Entities\Order::class)
            <button type="button" class="btn btn-outline-secondary" title="{{ __('Copy to clipboard') }}"
                    data-clip="P#{{ $entity->getSequence() }}" onclick="copyToClipboard($(this))">
                <span class="clip">{{ __("Copied") }}</span>
                <span class="bx bxs-copy"></span>
            </button>
        @endcan
        @can('update', $entity)
        <a href="{{ route('orders.edit', ['order' => $entity->getId()]) }}" class="btn btn-outline-secondary" title="{{ __('Edit') }}">
            <i class="bx bxs-pencil bx-xs bx-tada-hover"></i>
        </a>
        @endcan
        @can('delete', $entity)
        {{ Form::button('<i class="bx bx-xs bxs-trash-alt bx-tada-hover"></i>', [
            'class'    => 'btn btn-outline-secondary', 
            'type'     => 'submit', 
            'onclick'  => "return confirm('".__('delete.confirm')."')",
        ]) }}
        @endcan
    </div>
    {{ Form::close() }}
@endsection
 
@section('content')
<div class="row">
    <div class="table-responsive col-sm-12 col-md-6">
        <table class="table table-sm align-middle table-bordered border-white">
            <tr>
                <th colspan="2">{{ __('Account') }}</th>
                <th>{{ __('Area') }}</th>
            </tr>
            <tr class="table-secondary">
                <td colspan="2">
                    <a href="{{ route('accounts.show', ['account' => $entity->getAccount()->getId()]) }}" title="{{ $entity->getAccount()->getName() }}">{{ $entity->getAccount()->getSerial() }}</a>
                    <small class="text-muted">{{ $entity->getAccount()->getName() }}</small>
                </td>
                <td>
                    <!--<a href="{{ route('areas.show', ['area' => $entity->getArea()->getId()]) }}">-->
                        {{ $entity->getArea()->getAcronym() }}
                    <!--</a>-->
                    <span class="small text-muted">{{ $entity->getArea() }}</span>
                </td>
            </tr>
            <tr>
                <th>{{ __('Supplier') }}</th>
                <th>{{ __('Presupuesto') }}</th>
                <th>{{ __('Estimated') }}</th>
            </tr>
            <tr class="table-secondary">
                <td>
                    <a href="{{ route('suppliers.show', ['supplier' => $entity->getSupplier()->getId()]) }}">{{ $entity->getSupplier()->getName() }}</a>
                </td>
                <td>@if ($entity->getEstimated())
                       <!-- <a href='{{ asset("storage/{$entity->getEstimated()}") }}' target="_blank" title="{{__('Local storage')}}" class="me-2">
                            <i class="bx bx-xs bx-hdd"></i>
                        </a>-->
                    @else-@endif
                
                    @if ($entity->getFileId())
                        <a href="{{ $entity->getFileUrl() }}" target="_blank" title="{{ __('Google storage') }}">
                            <img src="/img/google/drive.png" alt="{{ __('Drive storage') }}" width="20px">
                        </a>
                    @else-@endif
                </td>
                <td>{{ $entity->getEstimatedCredit() ? number_format($entity->getEstimatedCredit(), 2, ",", ".").'€' : '-'}}</td>
            </tr>
            <tr>
                <th>{{ __('Invoice') }} <small class="text-muted">({{ __('Date') }})</small></th>
                <th colspan="2">{{ __('Credit') }}</th>
            </tr>
            <tr class="table-secondary">
                <td>
                    {{ $entity->getInvoice() ?? "-" }}
                    @if ($entity->getInvoiceDate())
                    <small class="text-muted">
                        ({{ $entity->getInvoiceDate()->format("D, d M Y") }})
                    </small>
                    @endif
                </td>
                <td colspan="2">{{ $entity->getCredit() ? number_format($entity->getCredit(), 2, ",", ".").'€' : '-'}}</td>
            </tr>
        </table>
    </div>
    <div class="table-responsive col-sm-12 col-md-6">
        <table class="table table-sm align-middle table-bordered border-white">
            <tr>
                <th>{{ __('User') }}
                    <small class="small text-muted">({{ __('Created') }})</small>
                </th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Receive in') }}</th>
            </tr>
            <tr class="table-secondary">
                <td>
                    {{ $entity->getUser()->getName() }}
                    <span class="small text-muted">{{ Carbon\Carbon::parse($entity->getCreated())->diffForHumans() }}</span>
                </td>
                <td>{{ $entity->getDate()->format("D, d M Y") }}</td>
                <td>{{ $entity->getReceiveInName() }}</td>
            </tr>
            @if (count($entity->getProducts())) 
                <tr>
                    <th colspan="2">{{ __('elementos') }}</th>
                    <th>{{ __('cantidad') }}</th>
                </tr>
                @foreach ($entity->getProducts() as $product)
                <tr class="table-secondary">
                    <td colspan="2">{{ $product->getDetail() }}</td>
                    <td>{{ $product->getUnits() }}</td>
                    <!--
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
                    -->
                </tr>
                @endforeach
            @endif
            <tr class="">
                <td colspan="4"><b>{{ __('Detail') }}:</b> {{ $entity->getDetail() }}</td>
            </tr>
        </table>
    </div>   
</div>

<ul class="nav nav-tabs justify-content-center border-0">
  <li class="nav-item">
    <a class='nav-link {{request()->is("orders/{$entity->getId()}", "orders/{$entity->getId()}/actions*")?" active":"" }}' href="{{ route('orders.show', ['order' => $entity->getId()]) }}">
    <span class="bx bx-xs bx-pulse"></span> {{ __('Activity') }}
    </a>
  </li>
  <li class="nav-item">
    <a class='nav-link {{request()->is("orders/{$entity->getId()}/incidences*")?" active":"" }}' href="{{ route('orders.incidences.index', ['order' => $entity->getId()]) }}">
    <span class="bx bx-xs bxs-bell"></span> {{ __('Incidences') }}
    </a>
  </li>
</ul>

<div class="bg-white border rounded rounded-5 px-2 mb-2">
    @yield('body', View::make('orders.body', [
        'collection' => $collection,
        'entity' => $entity,
        'perPage' => $perPage
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
