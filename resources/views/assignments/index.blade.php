@extends('new_layout')
@section('title'){{ __('Assignments') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('assignments.create') }}" class="btn btn-sm btn-outline-secondary">
    <span data-feather="plus"></span> New
    </a>
@endsection
@section('content')

    @include('assignments.shared.search', ['route' => route('assignments.index')])
    @include ('assignments.shared.table', ['collection' => $collection, 'pagination' => true])

@endsection
