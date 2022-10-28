@extends('new_layout')
@section('title'){{ __('Actions') }}@endsection
@section('btn-toolbar')
@endsection
@section('content')

@include('actions.shared.table', [
        'collection' => $collection, 
        'pagination' => true,
    ])
  
@endsection
