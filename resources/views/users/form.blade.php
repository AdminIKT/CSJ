@extends('sj_layout')

@section('title') 
@if ($entity->getId()) 
    {{ __("Edit user :email", ["email" => $entity->getEmail()]) }}
@else 
    {{ __("New user") }} 
@endif
@endsection

@section('content')
   
<form action="{{ $route }}" method="POST" class="row" novalidate>
    @csrf
    {{ method_field($method) }}

    <div class="col-md-4 mb-3">
        {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
        <div class="input-group input-group-sm">
            <span class="input-group-text">
                <i class="cbg {!! $entity->getStatusColor() !!}"></i>
            </span>
            {{ Form::select('status', [
                null => __('Selecciona'),
                \App\Entities\User::STATUS_INACTIVE => \App\Entities\User::statusName(\App\Entities\User::STATUS_INACTIVE),
                \App\Entities\User::STATUS_ACTIVE => \App\Entities\User::statusName(\App\Entities\User::STATUS_ACTIVE),
            ], old('status', $entity->getStatus()), ['class'=>'form-select form-select-sm' . ($errors->has('status') ? ' is-invalid':'')], [null => ['disabled' => true]]) }}
            @if ($errors->has('status'))
               <div class="invalid-feedback">{!! $errors->first('status') !!}</div>
            @endif
        </div>
    </div>

    <div class="col-md-8 mb-3">
        {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
        {{ Form::email('email', old('email', $entity->getEmail()), ['class' => 'form-control form-control-sm' . ($errors->has('email')? ' is-invalid':''), 'aria-describedby' => 'emailHelpBlock']) }}
        <div id="emailHelpBlock" class="form-text">
            {{ __("Email must be in ':domain' domain", ['domain' => 'fpsanjorge.com']) }}
        </div>
        @if ($errors->has('email'))
           <div class="invalid-feedback">{!! $errors->first('email') !!}</div>
        @endif

        <!--
        {{ Form::label('password', 'Password', ['class' => 'form-label']) }}
        {{ Form::password('password', ['class' => 'form-control' . ($errors->has('password') ? ' is-invalid':'')]) }}
        @if ($errors->has('password'))
           <div class="invalid-feedback">{!! $errors->first('password') !!}</div>
        @endif
        -->
    </div>

    <fieldset class="col-md-12 mb-3">
        <legend>{{ __('Roles') }}</legend>
        @foreach ($roles as $role)
        {{ Form::checkbox("roles[]", $role->getId(), $entity->hasRole($role), ['class' => 'form-check-input' . ($errors->has('roles') ? ' is-invalid':'')]) }}
        {{ Form::label("roles[]", $role->getName(), ['class' => 'form-check-label']) }}
        @endforeach
        @if ($errors->has("roles"))
           <div class="invalid-feedback">{!! $errors->first('roles') !!}</div>
        @endif
    </fieldset>

    <div class="col-md-12">
        <a href="{{ url()->previous() }}" class="btn btn-sm">{{__('cancelar')}}</a>
        {{ Form::submit(__('guardar'), ['class' => 'btn btn-primary btn-sm float-end']) }}
    </div>

</form>
@endsection
