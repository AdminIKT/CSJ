@extends('sj_layout')
@section('title'){{ __('InvoiceCharges') }}@endsection
@section('content')

{{ Form::open([
    'route' => ['imports.store'], 
    'method' => 'POST', 
    'class' => 'row',
    'novalidate' => true,
   ])
}}

<div class="table-responsive">
  <table class="table table-sm">
    <thead>
        <tr>
            <th scope="col">{{ __('Import') }}</th>
            <th scope="col">{{ __('Order') }}</th>
            <th scope="col">{{ __('Status') }}</th>
            <!--<th scope="col">{{ __('Account') }}</th>-->
            <th scope="col">{{ __('Credit') }}</th>
            <th scope="col">{{ __('Invoice') }}</th>
            <th scope="col">{{ __('Type') }}</th>
            <th scope="col">{{ __('Detail') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($collection as $i => $entity)
        <tr>
            <td>
            {{ Form::checkbox("item[$i]", true, old("item[$i]", $entity->getOrder() && $entity->getOrder()->isPending()), ['class' => 'form-check-input', 'disabled' => !($entity->getOrder() && $entity->getOrder()->isPending())]) }} 
            </td>
            <td>@if (null !== ($order = $entity->getOrder()))
                <a href="{{route('orders.show', ['order' => $order->getId()])}}">{{ $order->getSequence() }}</a>
            @else
                <span class="text-danger">{{ __('Order not found') }}</span>
            @endif</td>
            <td>@if (null !== ($order = $entity->getOrder()))
                <span class="{{ $order->isPending() ? '':'text-danger' }}">{{ $order->getStatusName() }}</span>
            @else
                <span class="text-danger">{{ __('Order not found') }}</span>
            @endif</td>
            <td>@if (null !== ($order = $entity->getOrder()) && $order->isPending())
                
                <div class="input-group input-group-sm mb-3">
                    {{ Form::number("credit[$i]", old("credit[$i]", $entity->getCredit()), ['step' => '0.01', 'min' => 0, 'class' => 'form-control' . ($errors->has('credit[$i]') ? ' is-invalid':'') ]) }}
                    <span class="input-group-text">€</span>
                </div>
            @else
                {{ $entity->getCredit() }}€
            @endif</td>
            <td>@if (null !== ($order = $entity->getOrder()) && $order->isPending())
                {{ Form::text("invoice[$i]", old("invoice[$i]", $entity->getInvoice()), ['class' => 'form-control form-control-sm' . ($errors->has('invoice[$i]') ? ' is-invalid':'')]) }}
            @else
                {{ $entity->getInvoice() }}
            @endif</td>
            <td>@if ($entity->getOrder() && $entity->getOrder()->isPending())
                {{ Form::select("type[$i]", [null => __('selecciona'), \App\Entities\InvoiceCharge::TYPE_INVOICED => \App\Entities\InvoiceCharge::typeName(\App\Entities\InvoiceCharge::TYPE_INVOICED)], old("type[$i]", $entity->getType()), ['class'=>'form-select form-select-sm' . ($errors->has('type[$i]') ? ' is-invalid': '')]) }}
            @else
                {{ $entity->getTypeName() }}
            @endif</td>
            <td>@if ($entity->getOrder() && $entity->getOrder()->isPending())
                {{ Form::textarea("detail[$i]", old("detail[$i]", $entity->getDetail()), ['class' => 'form-control form-control-sm' . ($errors->has("detail[$i]") ? ' is-invalid': ''), 'rows' => 1]) }}
            @else
                {{ $entity->getDetail() }}
            @endif</td>
        </tr>
        @endforeach
    </tbody>
  </table>
</div>
<div>
    <a href="{{ url()->previous() }}" class="btn btn-sm ">Cancel</a>
    {{ Form::submit(__('Import'), ['class' => 'btn btn-primary btn-sm float-end']) }}
</div>

    {{ Form::close() }}
@endsection
