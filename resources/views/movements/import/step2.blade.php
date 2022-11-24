@extends('sj_layout')
@section('title')
    {{ __('Import invoiceCharges') }}
@endsection
@section('content')

{{ Form::open([
    'route' => ['imports.store.step2'], 
    'method' => 'POST', 
    'class' => 'row',
    'novalidate' => true,
   ])
}}
    <table class="table table-sm  table-striped">
        <thead>
            <th scope="col" class="col">{{ __('Import') }}</th>
            <th scope="col" class="col-1">{{ __('Credit') }}</th>
            <th scope="col" class="col-1">{{ __('Invoice') }}</th>
            <th scope="col" class="col-1">{{ __('Date') }}</th>
            <!--<th scope="col">{{ __('Type') }}</th>-->
            <th scope="col" colspan="3">{{ __('Detail') }}</th>
        </thead>
        <tbody>
        @foreach ($collection as $i => $entity)
            @php $editable = (null !== ($order = $entity->getOrder()) && $order->isPayable()); @endphp  
            <tr class="border-white">
                <td>
                    <span class="cbg me-2 {{ $editable ? 'bg-success' : 'bg-danger' }}"></span>
                    {{ Form::checkbox("item[$i][import]", true, old("item.{$i}.import", $editable), [
                        'class'    => 'form-check-input ' . ($editable ? 'border-success' : 'border-danger'), 
                        'onclick'  => "rowState($(this))",
                        'disabled' => !$editable, 
                    ]) }} 
                </td>
                <td class="editable">
                    <div class="input-group input-group-sm">
                        {{ Form::number("item[{$i}][credit]", old("item.{$i}.credit", $entity->getCredit()), [
                            'step' => '0.01', 
                            'min' => 0, 
                            'class' => 'form-control' . ($errors->has("item.{$i}.credit") ? ' is-invalid':''),
                            'disabled' => !$entity->getOrder(), 
                        ]) }}
                        <span class="input-group-text">€</span>
                        @if ($errors->has("item.{$i}.credit"))
                            <div class="invalid-feedback">{!! $errors->first("item.{$i}.credit") !!}</div>
                        @endif
                    </div>
                </td>
                <td class="editable">
                    {{ Form::text("item[$i][invoice]", old("item.{$i}.invoice", $entity->getInvoice()), [
                        'class'    => 'form-control form-control-sm' . ($errors->has("item.{$i}.invoice") ? ' is-invalid':''),
                        'disabled' => !$entity->getOrder(), 
                    ]) }}
                    @if ($errors->has("item.{$i}.invoice"))
                        <div class="invalid-feedback">{!! $errors->first("item.{$i}.invoice") !!}</div>
                    @endif
                </td>
                <td class="editable">
                    {{ Form::date("item[$i][date]", old("item.{$i}.date", $entity->getInvoiceDate()), [
                        'class'    => 'form-control form-control-sm' . ($errors->has("item.{$i}.date") ? ' is-invalid':''), 
                        'disabled' => !$entity->getOrder(), 
                    ]) }}
                    @if ($errors->has("item.{$i}.date"))
                        <div class="invalid-feedback">{!! $errors->first("item.{$i}.date") !!}</div>
                    @endif
                </td>
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
                                'class'    =>'form-select form-select-sm' . ($errors->has('order[$i]') ? ' is-invalid': ''),
                        ]) }}
                    </div>
                </td>
                <td class="editable">
                    {{ Form::select("supplier[$i]", [
                        $order->getSupplier()->getId() => $order->getSupplier()->getName(),
                        ], 
                        old("supplier[$i]", $order->getSupplier()->getId()), [
                            'class'    =>'form-select form-select-sm' . ($errors->has('supplier[$i]') ? ' is-invalid': ''),
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
                </td>
                @endif
            </tr>
            <tr>
                <td colspan="7" class="text-end pt-0">
                    <small class="text-muted">
                    {{ $entity->getTypeName() }}:
                    @if (null !== ($order = $entity->getOrder()))
                        {!! __('<a href=":route" target="_blank">:account</a> on :date, estimated in :credit€', [
                            'route'   => route('accounts.show', ['account' => $order->getAccount()->getId()]), 
                            'account' => $order->getAccount(),
                            'date'    => $order->getDate()->format('d/m/Y'),
                            'credit'  => number_format($order->getEstimatedCredit(), 2, ",", "."),
                        ]) !!}
                        <a href="{{route('orders.show', ['order' => $order->getId()])}}" target="_blank">
                            <i class="bx bx-show"></i>
                        </a>
                    @else
                        {{ __('Order not found') }}
                    @endif
                    </small>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
            
    <div>
        {{ Form::button("<i class='bx bxs-file-import'></i> ". __('Import'), [
            'type'  => 'submit',
            'class' => 'btn btn-primary btn-sm float-end',
        ]) }}
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary">
            <i class='bx bx-x'></i> {{__('cancelar')}}
        </a>
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

        $(document).ready(function() {
            $('input:checkbox').each(function(i, el) {
                rowState($(el));
            });
        });

    </script>
@endsection
