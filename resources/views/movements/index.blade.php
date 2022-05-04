@extends('new_layout')
@section('title'){{ __('Movements') }}@endsection
@section('btn-toolbar')
@endsection
@section('content')
    @include('movements.shared.search', [
        'route' => route('movements.index'),
        'exclude' => []
    ])
    @include ('movements.shared.table', [
        'collection' => $collection, 
        'pagination' => true, 
        'exclude' => []
    ])
@endsection
