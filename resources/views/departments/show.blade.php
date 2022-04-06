@extends('new_layout')
@section('title')
    {{ __('Area :name', ['name' => $entity->getName()]) }}
@endsection

