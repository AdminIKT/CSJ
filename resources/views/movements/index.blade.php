@extends('sj_layout')
@section('title'){{ __('Movements') }}@endsection
@section('btn-toolbar')
    <!--<a href="{{ route('imports.create') }}" class="btn btn-sm btn-outline-secondary m-1 ms-0" title="{{__('Import')}}">
        <span data-feather="download-cloud"></span> {{ __('Import') }}
    </a>
    <a href="{{ route('invoiceCharges.create') }}" class="btn btn-sm btn-outline-secondary m-1" title="{{__('New')}}">
        <span data-feather="plus"></span> {{ __('New') }}
    </a>-->
@endsection
@section('content')
    <a href="{{ route('imports.create') }}" class="btn btn-sm btn-outline-secondary m-1 ms-0" title="{{__('Import')}}">
        <span data-feather="download-cloud"></span> {{ __('Import') }}
    </a>
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
