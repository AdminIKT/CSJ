<!-- FIXME in Shared view -->
@extends('sj_layout')
@section('title'){{ __('Accounts') }}@endsection
@section('btn-toolbar')
@endsection
@section('content')
    <a href="{{ route('accounts.create') }}" class="btn btn-sm btn-outline-secondary m-1 ms-0" title="{{__('New')}}">
        <span data-feather="plus"></span> {{__('New')}}
    </a>
    @include('accounts.shared.search', [
        'route' => route('accounts.index'),
        'report' => true,
    ])

    <div class="bg-white border rounded rounded-5 px-2 mb-2">
        @include('accounts.shared.table', [
                'collection' => $accounts, 
                'pagination' => true,
            ])
    </div>
@endsection
