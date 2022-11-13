@extends('suppliers.show')
 
@section('body')
<div class="table-responsive">
    <table class="table table-sm align-middle">
        <tr>
            <th>{{ __('Year') }}</th>
            <th>{{ __('Predicted') }}</th>
            <th>{{ __('Invoiced') }}</th>
            <th>{{ __('Updated') }}</th>
        </tr>
        @foreach ($entity->getInvoiced() as $invoiced)
        <tr>
            <td>{{ $invoiced->getYear() }}</td>
            <td>{{ number_format($invoiced->getEstimated(), 2, ",", ".")}} €</td>
            <td>{{ number_format($invoiced->getCredit(), 2, ",", ".")}} €</td>
            <td>{{ $invoiced->getUpdated()->format("d/m/Y H:i") }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
