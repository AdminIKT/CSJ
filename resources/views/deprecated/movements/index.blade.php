@extends('new_layout')
@section('title'){{ __('InvoiceCharges') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('imports.create') }}" class="btn btn-sm btn-outline-secondary me-2" title="{{__('Import')}}">
        <span data-feather="download-cloud"></span> {{ __('Import') }}
    </a>
    <a href="{{ route('invoiceCharges.create') }}" class="btn btn-sm btn-outline-secondary" title="{{__('New')}}">
        <span data-feather="plus"></span> {{ __('New') }}
    </a>
@endsection

@section('content')

@include('invoiceCharges.shared.search', ['route' => route('invoiceCharges.index')])
  
@include ('invoiceCharges.shared.table', ['collection' => $collection, 'pagination' => true])
  
@endsection