@extends('sj_layout')
@section('title')
    {{ __('Import charges (:charge)', ['charge' => \App\Entities\InvoiceCharge::typeName($type)]) }}
@endsection
@section('content')

{{ Form::open([
    'route' => ['imports.store.step2'], 
    'method' => 'POST', 
    'class' => 'row',
    'novalidate' => true,
   ])
}}
    <table class="table table-sm">
        <thead>
            <th scope="col" class="col">{{ __('Import') }}</th>
            <th scope="col" class="col">{{ __('Credit') }}</th>
            <th scope="col" class="col">{{ __('Invoice') }}</th>
            <th scope="col" class="col">{{ __('Date') }}</th>
            <th scope="col" class="col">{{ __('HZ code') }} <small class="text-muted">{{ __('Year') }}-{{ __('Entry') }}</small></th>
            <th scope="col" class="col" colspan="3">{{ __('Detail') }}</th>
        </thead>
        <tbody>
        @foreach ($collection as $i => $entity)
            <tr class="border-white">
                @php $editable = $entity->getId() === null
                                  && (get_class($entity) !== App\Entities\Charge::class)
                                  && (get_class($entity) === App\Entities\InvoiceCharge::class
                                  && $entity->getSubaccount() !== null
                                  ) 
                                  || (get_class($entity) === App\Entities\OrderCharge::class
                                  && null !== ($order = $entity->getOrder())
                                  && $order->isPayable()
                                  ); 
                @endphp  
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
                        ]) }}
                        <span class="input-group-text">€</span>
                        @if ($errors->has("item.{$i}.credit"))
                            <div class="invalid-feedback">{!! $errors->first("item.{$i}.credit") !!}</div>
                        @endif
                    </div>
                </td>
                @if ($entity instanceof \App\Entities\InvoiceCharge)
                <td class="editable">
                    {{ Form::text("item[$i][invoice]", old("item.{$i}.invoice", $entity->getInvoice()), [
                        'class'    => 'form-control form-control-sm' . ($errors->has("item.{$i}.invoice") ? ' is-invalid':''),
                    ]) }}
                    @if ($errors->has("item.{$i}.invoice"))
                        <div class="invalid-feedback">{!! $errors->first("item.{$i}.invoice") !!}</div>
                    @endif
                </td>
                <td class="editable">
                    {{ Form::date("item[$i][invoiceDate]", old("item.{$i}.invoiceDate", $entity->getInvoiceDate()), [
                        'class'    => 'form-control form-control-sm' . ($errors->has("item.{$i}.invoiceDate") ? ' is-invalid':''), 
                    ]) }}
                    @if ($errors->has("item.{$i}.invoiceDate"))
                        <div class="invalid-feedback">{!! $errors->first("item.{$i}.invoiceDate") !!}</div>
                    @endif
                </td>
                <td class="editable">
                    <div class="input-group input-group-sm">
                        {{ Form::text("item[$i][hzyear]", old("item.{$i}.hzyear", $entity->getHzYear()), [
                            'class'    => 'form-control form-control-sm' . ($errors->has("item.{$i}.hzyear") ? ' is-invalid':''),
                        ]) }}
                        <span class="input-group-text">-</span>
                        {{ Form::text("item[$i][hzentry]", old("item.{$i}.hzentry", $entity->getHzEntry()), [
                            'class'    => 'form-control form-control-sm' . ($errors->has("item.{$i}.hzentry") ? ' is-invalid':''),
                        ]) }}
                        @if ($errors->has("item.{$i}.hzyear"))
                            <div class="invalid-feedback">{!! $errors->first("item.{$i}.hzyear") !!}</div>
                        @endif
                        @if ($errors->has("item.{$i}.hzentry"))
                            <div class="invalid-feedback">{!! $errors->first("item.{$i}.hzentry") !!}</div>
                        @endif
                    </div>
                </td>
                @else
                <td colspan="3" class="text-center">-</td>
                @endif

                @if ($entity instanceof App\Entities\OrderCharge && null !== ($order = $entity->getOrder()))
                    <td class="editable">
                        {{ Form::hidden("item[$i][type]", $entity->getType()) }}
                        {{ Form::hidden("item[$i][charge]", App\Entities\OrderCharge::HZ_PREFIX) }}
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white">
                                <i class="badge {!! $order->getStatusColor() !!}">{{ $order->getStatusName() }}</i>
                            </span>
                            {{ Form::select("item[$i][order]", [
                                $order->getId() => $order->getSequence(),
                                ], 
                                old("item.{$i}.order", $entity->getOrder()->getId()), [
                                    'class'    =>'form-select form-select-sm' . ($errors->has("item.{$i}.order") ? ' is-invalid': ''),
                            ]) }}
                            @if ($errors->has("item.{$i}.order"))
                                <div class="invalid-feedback">{!! $errors->first("item.{$i}.order") !!}</div>
                            @endif
                        </div>
                    </td>
                    <td class="editable">
                        {{ Form::select("item[$i][supplier]", [
                            $order->getSupplier()->getId() => $order->getSupplier()->getName(),
                            ], 
                            old("item.{$i}.supplier", $order->getSupplier()->getId()), [
                                'class'    =>'form-select form-select-sm' . ($errors->has("item.{$i}.supplier") ? ' is-invalid': ''),
                        ]) }}
                        @if ($errors->has("item.{$i}.supplier"))
                            <div class="invalid-feedback">{!! $errors->first("item.{$i}.supplier") !!}</div>
                        @endif
                    </td>
                    <td class="editable">
                        {{ Form::textarea("item[$i][detail]", old("item.{$i}.detail", $entity->getDetail()), [
                            'class'    => 'form-control form-control-sm' . ($errors->has("item.{$i}.detail") ? ' is-invalid': ''),
                            'rows'     => 1,
                        ]) }}
                        @if ($errors->has("item.{$i}.detail"))
                            <div class="invalid-feedback">{!! $errors->first("item.{$i}.detail") !!}</div>
                        @endif
                    </td>
                @elseif (get_class($entity) === App\Entities\InvoiceCharge::class && null !== ($acc = $entity->getSubaccount()))
                    <td class="editable">
                        {{ Form::hidden("item[$i][type]", $entity->getType()) }}
                        {{ Form::hidden("item[$i][charge]", App\Entities\InvoiceCharge::HZ_PREFIX) }}
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white">
                                <i class="cbg {!! $acc->getAccount()->getStatusColor() !!}"></i>
                            </span>
                            {{ Form::select("item[$i][account]", [null => __('selecciona')] + 
                                array_combine(
                                    $acc->getAccount()->getSubaccounts()->map(function($s) {
                                        return $s->getId();
                                    })->toArray(),
                                    $acc->getAccount()->getSubaccounts()->map(function($s) {
                                        return $s->getSerial();
                                    })->toArray()
                                ),
                                old("item.{$i}.account", $acc->getId()), [
                                    'class'    =>'form-select form-select-sm' . ($errors->has("item.{$i}.account") ? ' is-invalid': ''),
                                ],
                                [null => ["disabled" => true]]
                            ) }}
                            @if ($errors->has("item.{$i}.account"))
                                <div class="invalid-feedback">{!! $errors->first("item.{$i}.account") !!}</div>
                            @endif
                        </div>
                    </td>
                    <td colspan="2" class="editable">
                        {{ Form::textarea("item[$i][detail]", old("item.{$i}.detail", $entity->getDetail()), [
                            'class'    => 'form-control form-control-sm' . ($errors->has("item.{$i}.detail") ? ' is-invalid': ''),
                            'rows'     => 1,
                        ]) }}
                        @if ($errors->has("item.{$i}.detail"))
                            <div class="invalid-feedback">{!! $errors->first("item.{$i}.detail") !!}</div>
                        @endif
                    </td>
                @else
                    <td colspan="3">
                        {{ Form::textarea("item[$i][detail]", old("item.{$i}.detail", $entity->getDetail()), [
                            'class'    => 'form-control form-control-sm' . ($errors->has("item.{$i}.detail") ? ' is-invalid': ''),
                            'rows'     => 1,
                            'disabled' => true, 
                        ]) }}
                    </td>
                @endif
            </tr>
            <tr>
                <td></td>
                <td colspan="7" class="text-start pt-0">
                    <small class="">{{ $entity->getTypeName() }}:</small>
                    @if ($entity instanceof App\Entities\OrderCharge)
                        @if (null !== ($order = $entity->getOrder()))
                            <small class="text-muted">
                            {!! __(':account on :date estimated in :credit€', [
                                'account' => $order->getAccount(),
                                'date'    => $order->getDate()->format('d/m/Y'),
                                'credit'  => number_format($order->getEstimatedCredit(), 2, ",", "."),
                            ]) !!}
                            </small>
                            <a href="{{route('orders.show', ['order' => $order->getId()])}}" target="_blank">
                                <i class="bx bx-show"></i>
                            </a>
                        @else
                            <small class="text-danger">{{ __('Order not found') }}</small>
                        @endif
                    @elseif ($entity instanceof App\Entities\InvoiceCharge) 
                        @if (null !== ($acc = $entity->getSubaccount()))
                            <small class="text-muted">{{ $acc->getAccount() }}</small>
                            <a href="{{route('accounts.show', ['account' => $acc->getAccount()->getId()])}}" target="_blank">
                                <i class="bx bx-show"></i>
                            </a>
                        @else
                            <small class="text-danger">{{ __('Account not found') }}</small>
                        @endif
                    @endif
                    @if ($entity->getId())
                        <small class="text-danger">({{ __('Charge :code allready imported on :created', [
                            'code'    => $entity->getHzCode(),
                            'created' => $entity->getCreated()->format('D, d M Y H:i'),
                        ]) }})</small>
                    @endif
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
        <a href="{{ url()->previous() }}" class="btn btn-sm">
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
