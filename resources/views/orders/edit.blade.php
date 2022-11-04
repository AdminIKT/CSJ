@extends('sj_layout')
@section('title')
    {{ __('Edit order nº :number', ['number' => $entity->getSequence()]) }}
@endsection
