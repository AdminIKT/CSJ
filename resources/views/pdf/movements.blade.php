@extends('pdf_layout')

@section('title')
{{ __('Account movements') }}
@endsection

@section('content')
    @php
        $accountName = $entity->getSerial() . ' - ' . $entity->getName();
        $totalCredit = 0;
    @endphp

    <h1 style="text-align:center; color:#52bdc3; margin-bottom:20px; font-size:21px;">{{ __('Account') }}: {{ $accountName }}</h1>

    <table width="25%" style="margin-bottom:2px; font-size:12px;">
        <tr>
            <td style="font-weight:bold;">{{ __('Created') }}:</td>
            <td>{{ now()->format('d/m/Y') }}</td>
        </tr>
    </table>

    <table width="100%" style="border-collapse: collapse; font-size:12px;">
        <thead>
            <tr>
                <th style="border:1px solid #444; padding:4px; text-align:left;">{{ __('Date') }}</th>
                <th style="border:1px solid #444; padding:4px; text-align:left;">{{ __('Asiento') }}</th>
                <th style="border:1px solid #444; padding:4px; text-align:left;">{{ __('Factura') }}</th>
                <th style="border:1px solid #444; padding:4px; text-align:left;">{{ __('Supplier') }}</th>
                <th style="border:1px solid #444; padding:4px; text-align:left;">{{ __('Detail') }}</th>
                <th style="border:1px solid #444; padding:4px; text-align:right;">{{ __('Ingresos') }}</th>
                <th style="border:1px solid #444; padding:4px; text-align:right;">{{ __('Gastos') }}</th>
                <th style="border:1px solid #444; padding:4px; text-align:right;">{{ __('importe') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($collection as $entity)
                @php
                    if ($entity instanceof \App\Entities\Assignment) {
                        $totalCredit += $entity->getCredit();
                    } else {
                        $totalCredit -= $entity->getCredit();
                    }
                @endphp
                <tr>

                    <td style="border:1px solid #444; padding:4px;">{{ $entity->getCreated()->format('d/m/Y') }}</td>
                    <td style="border:1px solid #444; padding:4px;">@if (method_exists($entity, 'getHzCode')){{ $entity->getHzCode() }}@endif</td>
                    <td style="border:1px solid #444; padding:4px;">@if (method_exists($entity, 'getInvoice')){{ $entity->getInvoice() }}@endif</td>
                    <td style="border:1px solid #444; padding:4px;">@if ($entity instanceof \App\Entities\OrderCharge){{ $entity->getOrder()->getSupplier()->getName() }}@endif</td>
                    <td style="border:1px solid #444; padding:4px;">{{ $entity->getDetail() }}</td>
                    <td style="border:1px solid #444; padding:4px; text-align:right;">@if ($entity instanceof \App\Entities\Assignment){{ number_format($entity->getCredit(), 2, ',', '.') }}€@endif</td>
                    <td style="border:1px solid #444; padding:4px; text-align:right;">@if (! $entity instanceof \App\Entities\Assignment){{ number_format($entity->getCredit(), 2, ',', '.') }}€@endif</td>
                    <td style="border:1px solid #444; padding:4px; text-align:right;">@if ($entity instanceof \App\Entities\Assignment)+@elseif (! $entity instanceof \App\Entities\Assignment)-@endif{{ number_format($entity->getCredit(), 2, ',', '.') }}€</td>

                </tr>
            @endforeach
            @if ($collection->total())
                <tr>
                    <td colspan="7" style="border:1px solid #444; padding:8px; font-weight:bold; text-align:right;">{{ __('Total') }}:</td>
                    <td style="border:1px solid #444; padding:8px; text-align:right; font-weight:bold;">{{ number_format($totalCredit, 2, ',', '.') }}€</td>
                </tr>
            @endif
        </tbody>
    </table>

@endsection
