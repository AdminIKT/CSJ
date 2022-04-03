@extends('suppliers.show')

@section('body')
<h4 class="py-2">@if ($contact->getId()) {{__('Edit contact')}} @else New contact @endif</h4>
<form action="{{ $route }}" method="POST" class="row">
    @csrf
    {{ method_field($method ?? 'POST') }}
    <div class="col-md-6 mb-1 has-validations">
        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
        {{ Form::text("name", old('name', $contact->getName()), ["class" => "form-control form-control-sm" . ($errors->has("name") ? " is-invalid":"")]) }}
        @if ($errors->has("name"))
           <div class="invalid-feedback">{!! $errors->first("name") !!}</div>
        @endif
    </div>
    <div class="col-md-6 mb-1 has-validations">
        {{ Form::label('position', __('Position'), ['class' => 'form-label']) }}
        {{ Form::text("position", old('position', $contact->getPosition()), ["class" => "form-control form-control-sm" . ($errors->has("position") ? " is-invalid":"")]) }}
        @if ($errors->has("position"))
           <div class="invalid-feedback">{!! $errors->first("position") !!}</div>
        @endif
    </div>
    <div class="col-md-6 mb-1 has-validations">
        {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
        {{ Form::text("email", old('email', $contact->getEmail()), ["class" => "form-control form-control-sm" . ($errors->has("email") ? " is-invalid":"")]) }}
        @if ($errors->has("email"))
           <div class="invalid-feedback">{!! $errors->first("email") !!}</div>
        @endif
    </div>
    <div class="col-md-6 mb-1 has-validations">
        {{ Form::label('phone', __('Phone'), ['class' => 'form-label']) }}
        {{ Form::text("phone", old('phone', $contact->getPhone()), ["class" => "form-control form-control-sm" . ($errors->has("phone") ? " is-invalid":"")]) }}
        @if ($errors->has("phone"))
           <div class="invalid-feedback">{!! $errors->first("phone") !!}</div>
        @endif
    </div>
    <div class="col-md-12">
        <a href="{{ url()->previous() }}" class="btn btn-sm">{{__('cancelar')}}</a>
        {{ Form::submit(__('guardar'), ['class' => 'btn btn-sm btn-primary float-end']) }}
    </div>
    @if (isset($dst))
        {{ Form::hidden('destination', $dst ?? '') }}
    @endif
</form>
@endsection
 
