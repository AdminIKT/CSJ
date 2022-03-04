@extends('suppliers.show')
 
@section('body')
<div class="table-responsive">
    <table class="table table-sm table-hover">
        <tr>
            <th>Year</th>
            <th>Credit</th>
            <th>Updated</th>
        </tr>
        @foreach ($entity->getInvoiced() as $invoiced)
        <tr>
            <td>{{ $invoiced->getYear() }}</td>
            <td>{{ $invoiced->getCredit() }}€</td>
            <td>{{ $invoiced->getUpdated()->format("d/m/Y H:i") }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
