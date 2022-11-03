@extends('sj_layout')
@section('title'){{ __('Activity') }}@endsection
@section('content')

<div class="bg-white border rounded rounded-5 px-2 mb-2">
    @include('actions.shared.table', [
            'collection' => $collection, 
            'pagination' => true,
        ])
</div>
  
@endsection
