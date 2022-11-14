@extends('sj_layout')
@section('title'){{ __('Order receptions') }}@endsection
@section('btn-toolbar')@endsection
@section('content')

@include('orders.shared.search', [
        'route'  => route('orders.receptions'),
        'exclude' => ['status'],
    ])

<div class="bg-white border rounded rounded-5 px-2 mb-2">
    @include('orders.shared.table', [
            'collection' => $collection, 
            'pagination' => true,
        ])
</div>
  
@endsection
