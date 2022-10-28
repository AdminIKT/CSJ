@extends('new_layout')
@section('title'){{ __('Activity') }}@endsection
@section('btn-toolbar')
@endsection
@section('content')

@include('actions.shared.table', [
        'collection' => $collection, 
        'pagination' => true,
    ])
  
@endsection
