@extends('new_layout')
@section('title'){{ __('Movements') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('imports.create') }}" class="btn btn-sm btn-outline-secondary me-2" title="{{__('Import')}}">
        <span data-feather="download-cloud"></span> {{ __('Import') }}
    </a>
    <a href="{{ route('movements.create') }}" class="btn btn-sm btn-outline-secondary" title="{{__('New')}}">
        <span data-feather="plus"></span> {{ __('New') }}
    </a>
@endsection

@section('content')
  
@include ('movements.shared.table', ['collection' => $collection, 'pagination' => true])
  
@endsection
