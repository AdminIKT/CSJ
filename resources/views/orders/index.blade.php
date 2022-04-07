@extends('new_layout')
@section('title'){{ __('Orders') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('reports.orders', request()->input()) }}" class="btn btn-sm btn-outline-secondary" title="{{__('Report')}}" target="_blank">
        <span data-feather="bar-chart-2"></span> {{__('Report')}}
    </a>
@endsection
@section('content')

@include('orders.shared.search', ['route' => route('orders.index')])

@include('orders.shared.table', ['collection' => $collection, 'pagination' => true])
  
@endsection
