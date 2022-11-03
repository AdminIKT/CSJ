@extends('sj_layout')
@section('title'){{ __('Suppliers') }}@endsection
@section('btn-toolbar')
    <!--
    <a href="{{ route('suppliers.create') }}" class="btn btn-sm btn-outline-secondary">
        <span data-feather="plus"></span> {{ __('New') }}
    </a>
    -->
@endsection
@section('content')

    <a href="{{ route('suppliers.create') }}" class="btn btn-sm btn-outline-secondary m-1 ms-0">
        <span data-feather="plus"></span> {{ __('New') }}
    </a>
    @include('suppliers.shared.search', [
            'route'  => route('suppliers.index'),
            'report' => true,
        ])
    
    <div class="bg-white border rounded rounded-5 px-2 mb-2">
        @include('suppliers.shared.table', [
                'collection' => $collection, 
                'pagination' => true,
            ])
    </div>
  
@endsection
