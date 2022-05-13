@extends('report_layout')
@section('content')
@include('orders.shared.table', [
        'collection' => $collection, 
        'exclude' => ['actions', 'users']
    ])
@endsection
