@extends('sj_layout')
@section('title')
    {{ __('Import invoiceCharges') }}
@endsection
@section('content')

{{ Form::open([
    'route' => ['imports.store'], 
    'method' => 'POST', 
    'class' => 'row',
    'novalidate' => true,
   ])
}}

  <table class="table table-sm">
    <thead>
        <th scope="col">{{ __('Import') }}</th>
        <th scope="col">{{ __('Credit') }}</th>
        <th scope="col">{{ __('Invoice') }}</th>
        <th scope="col">{{ __('Date') }}</th>
        <!--<th scope="col">{{ __('Type') }}</th>-->
        <th scope="col" colspan="3">{{ __('Detail') }}</th>
    </thead>
    <tbody>
        @foreach ($collection as $i => $entity)
        <tr>
            <td>
                <span class="cbg me-2 {{ $entity->getOrder() ? 'bg-success' : 'bg-danger' }}"></span>
                {{ Form::checkbox("item[$i][import]", true, old("item[$i][import]", $entity->getOrder() !== null), [
                    'class'    => 'form-check-input ' . ($entity->getOrder() ? 'border-success' : 'border-danger'), 
                    'onclick'  => "rowState($(this))",
                    'disabled' => !$entity->getOrder(), 
                ]) }} 
            </td>
            <td class="editable">
                <div class="input-group input-group-sm mb-3">
                    {{ Form::number("item[$i][credit]", old("item[$i][credit]", $entity->getCredit()), [
                        'step' => '0.01', 
                        'min' => 0, 
                        'class' => 'form-control' . ($errors->has("item[$i][credit]") ? ' is-invalid':''),
                        'disabled' => !$entity->getOrder(), 
                    ]) }}
                    <span class="input-group-text">€</span>
                </div>
            </td>
            <td class="editable">
                {{ Form::text("item[$i][invoice]", old("item[$i][invoice]", $entity->getInvoice()), [
                    'class'    => 'form-control form-control-sm' . ($errors->has("item[$i][invoice]") ? ' is-invalid':''),
                    'disabled' => !$entity->getOrder(), 
                ]) }}
            </td>
            <td class="editable">
                {{ Form::date("item[$i][date]", old("item[$i][date]", $entity->getInvoiceDate()), [
                    'class'    => 'form-control form-control-sm' . ($errors->has("item[$i][date]") ? ' is-invalid':''), 
                    'disabled' => !$entity->getOrder(), 
                ]) }}
            </td>
            <!--<td class=""><small>{{ __('Invoice charge') }}</small></td>-->
            @if (null !== ($order = $entity->getOrder()))
            <td class="editable">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white">
                        <i class="badge {!! $order->getStatusColor() !!}">{{ $order->getStatusName() }}</i>
                    </span>
                    {{ Form::select("order[$i]", [
                        $order->getId() => $order->getSequence(),
                        ], 
                        old("order[$i]", $entity->getOrder()->getId()), [
                            'class'=>'form-select form-select-sm' . ($errors->has('order[$i]') ? ' is-invalid': ''),
                    ]) }}
                </div>
                <small class="text-muted">{{ __(':area on :date, estimated in :credit€', [
                    'area'   => $order->getArea(), 
                    'date'   => $order->getDate()->format('d/m/Y'),
                    'credit' => number_format($order->getEstimatedCredit(), 2, ",", "."),
                ]) }}</small>
            </td>
            <td class="editable">
                {{ Form::select("supplier[$i]", [
                    $order->getSupplier()->getId() => $order->getSupplier()->getName(),
                    ], 
                    old("supplier[$i]", $order->getSupplier()->getId()), [
                        'class'=>'form-select form-select-sm' . ($errors->has('supplier[$i]') ? ' is-invalid': ''),
                ]) }}
            </td>
            <td class="editable">
                {{ Form::textarea("detail[$i]", old("detail[$i]", $entity->getDetail()), [
                    'class'    => 'form-control form-control-sm' . ($errors->has("detail[$i]") ? ' is-invalid': ''),
                    'rows'     => 1,
                ]) }}
            </td>
            @else
            <td colspan="3">
                {{ Form::textarea("detail[$i]", old("detail[$i]", $entity->getDetail()), [
                    'class'    => 'form-control form-control-sm' . ($errors->has("detail[$i]") ? ' is-invalid': ''),
                    'rows'     => 1,
                    'disabled' => true, 
                ]) }}
                <small class="text-muted">{{ __('Order not found') }}</small>
            </td>
            @endif
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
@section('scripts')
    <script>
        function rowState(input) {
            input.closest('tr')
                 .find('td.editable :input')
                 .each(function(i,el) {
                    $(el).attr('disabled', !input.prop('checked'));
                 });
        }
    </script>
@endsection


