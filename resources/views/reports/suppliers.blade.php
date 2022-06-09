@extends('report_layout')
@section('content')
@include('suppliers.shared.table', [
        'collection' => $collection, 
        'exclude' => ['users', 'created', 'actions']
    ])
@endsection
