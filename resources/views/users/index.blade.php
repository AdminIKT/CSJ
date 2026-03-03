@extends('sj_layout')
@section('title'){{ __('Users') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('users.create') }}" class="btn btn-sm btn-outline-secondary m-1 ms-0">
        <span data-feather="plus"></span> {{__('New')}}
    </a>
@endsection
@section('content')
@include('users.shared.search', [
        'route'  => route('users.index'),
    ])

<div class="bg-white border rounded rounded-5 px-2 mb-2">
    @include('users.shared.table', [
        'collection' => $collection,
        'pagination' => $pagination ?? false,
    ])
</div>
@endsection

