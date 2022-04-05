@extends('new_layout')
@section('title')
    {{ __('Department :name', ['name' => $entity->getName()]) }}
@endsection

