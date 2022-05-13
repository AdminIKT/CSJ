@extends('new_layout')
@section('title'){{ __('Orders') }}@endsection
@section('btn-toolbar')
@endsection
@section('content')

@include('orders.shared.search', ['route' => route('orders.index')])

@include('orders.shared.table', [
        'collection' => $collection, 
        'pagination' => true
    ])
  
@endsection
