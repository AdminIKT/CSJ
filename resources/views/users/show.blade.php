@extends('sj_layout')
@section('title')
    {{ __('User :email', ['email' => $entity->getEmail()]) }}
@endsection

