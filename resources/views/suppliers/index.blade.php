@extends('new_layout')
@section('title'){{ __('Suppliers') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('suppliers.create') }}" class="btn btn-sm btn-outline-secondary">
    <span data-feather="plus"></span> {{ __('New') }}
    </a>
@endsection
@section('content')

@include('suppliers.shared.search', [
        'route'  => route('suppliers.index'),
        'report' => true,
    ])

@include('suppliers.shared.table', [
        'collection' => $collection, 
        'pagination' => true,
    ])
  
@endsection
