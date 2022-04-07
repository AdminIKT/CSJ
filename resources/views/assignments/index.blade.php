@extends('new_layout')
@section('title'){{ __('Assignments') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('reports.assignments', request()->input()) }}" class="btn btn-sm btn-outline-secondary" title="{{__('Report')}}">
        <span data-feather="bar-chart-2"></span> {{__('Report')}}
    </a>
    <a href="{{ route('assignments.create') }}" class="btn btn-sm btn-outline-secondary ms-1">
        <span data-feather="plus"></span> {{ __('New') }}
    </a>
@endsection
@section('content')

    @include('assignments.shared.search', ['route' => route('assignments.index')])
    @include ('assignments.shared.table', ['collection' => $collection, 'pagination' => true])

@endsection
