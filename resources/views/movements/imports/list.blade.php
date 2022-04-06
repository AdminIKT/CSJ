@extends('new_layout')
@section('title'){{ __('Movements') }}@endsection
@section('content')

{{ Form::open([
    'route' => ['imports.store'], 
    'method' => 'POST', 
    'class' => 'row',
    'novalidate' => true,
   ])
}}

<div class="table-responsive">
  <table class="table table-hover table-sm">
    <thead>
        <tr>
            <th scope="col">{{ __('Import') }}</th>
            <th scope="col">{{ __('Order') }}</th>
            <th scope="col">{{ __('Status') }}</th>
            <!--<th scope="col">{{ __('Cuenta') }}</th>-->
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
            <td>@if ($entity->getOrder())
                <a href="{{route('orders.show', ['order' => $entity->getOrder()->getId()])}}">{{ $entity->getOrder()->getSequence() }}</a>
            @else
                <span class="text-danger">{{ __('Order not found') }}</span>
            @endif</td>
            <td>@if ($entity->getOrder())
                <span class="{{ $entity->getOrder()->isPending() ? '':'text-danger' }}">{{ $entity->getOrder()->getStatusName() }}</span>
            @else
                <span class="text-danger">{{ __('Order not found') }}</span>
            @endif</td>
            <!--<td>@if ($entity->getOrder())
                <a href="{{route('areas.show', ['area' => $entity->getArea()->getId()])}}">{{ $entity->getArea()->getName() }}-{{ $entity->getArea()->getType() }}</a>
            @else
                <span class="text-danger">{{ __('Order not found') }}</span>
            @endif</td>-->
            <td>@if ($entity->getOrder() && $entity->getOrder()->isPending())
                
                <div class="input-group input-group-sm mb-3">
                    {{ Form::number("credit[$i]", old("credit[$i]", $entity->getCredit()), ['step' => '0.01', 'min' => 0, 'class' => 'form-control' . ($errors->has('credit[$i]') ? ' is-invalid':'') ]) }}
                    <span class="input-group-text">€</span>
                </div>
            @else
                {{ $entity->getCredit() }}€
            @endif</td>
            <td>@if ($entity->getOrder() && $entity->getOrder()->isPending())
                {{ Form::text("invoice[$i]", old("invoice[$i]", $entity->getInvoice()), ['class' => 'form-control form-control-sm' . ($errors->has('invoice[$i]') ? ' is-invalid':'')]) }}
            @else
                {{ $entity->getInvoice() }}
            @endif</td>
            <td>@if ($entity->getOrder() && $entity->getOrder()->isPending())
                {{ Form::select("type[$i]", [null => '--Select one--', \App\Entities\Movement::TYPE_INVOICED => \App\Entities\Movement::typeName(\App\Entities\Movement::TYPE_INVOICED)], old("type[$i]", $entity->getType()), ['class'=>'form-select form-select-sm' . ($errors->has('type[$i]') ? ' is-invalid': '')]) }}
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
