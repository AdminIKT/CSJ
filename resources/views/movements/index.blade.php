@extends('sj_layout')
@section('title'){{ __('Movements') }}@endsection
@section('btn-toolbar')
@endsection
@section('content')
    <div class="btn-group m-1">
        <button id="importBtn" class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span data-feather="download-cloud"></span> {{ __('Import') }}
        </button>
        <ul class="dropdown-menu" aria-labelledby="importBtn">
            <li>
                <a href="{{ route('imports.create.step1', ['charge' => \App\Entities\InvoiceCharge::TYPE_CASH]) }}" class="dropdown-item" title="{{__('Cobro en caja')}}">
                    {{ App\Entities\InvoiceCharge::typeName(App\Entities\InvoiceCharge::TYPE_CASH) }}
                </a>
            </li>
            <li>
                <a href="{{ route('imports.create.step1', ['charge' => \App\Entities\InvoiceCharge::TYPE_INVOICED]) }}" class="dropdown-item" title="{{__('Transfer charge')}}">
                    {{ __('Transfer charge') }}
                </a>
            </li>
        </ul>
    </div>

    <a href="{{ route('invoiceCharges.create') }}" class="btn btn-sm btn-outline-secondary m-1" title="{{__('New')}}">
        <span data-feather="plus"></span> {{ __('New invoiceCharge') }}
    </a>
    @include('movements.shared.search', [
        'route' => route('movements.index'),
        'report' => true,
    ])
    <div class="bg-white border rounded rounded-5 px-2 mb-2">
        @include ('movements.shared.table', [
            'collection' => $collection, 
            'pagination' => true, 
            'exclude' => []
        ])
    </div>
@endsection
