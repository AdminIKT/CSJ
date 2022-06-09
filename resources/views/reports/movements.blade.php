@extends('report_layout')
@section('content')
@include('movements.shared.table', [
        'collection' => $collection, 
        'exclude' => ['actions', 'users']
    ])
@endsection
