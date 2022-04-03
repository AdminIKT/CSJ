@extends('new_layout')
@section('title')
@if ($entity->getId()) 
    {{ __('Edit department :name', ['name' => $entity->getName()]) }} 
@else 
    {{ __('New department') }} 
@endif
@endsection

@section('content')
   
<form action="{{ $route }}" method="POST" class="row" novalidate>
    @csrf
    {{ method_field($method) }}

    <div class="mb-3 has-validations">
        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
        {{ Form::text('name', old('name', $entity->getName()), ['class' => 'form-control form-control-sm' . ($errors->has('name') ? ' is-invalid' :'')]) }}
        @if ($errors->has('name'))
           <div class="invalid-feedback">{!! $errors->first('name') !!}</div>
        @endif
    </div>

    <div>
        {{ Form::submit(__('guardar'), ['class' => 'btn btn-primary btn-sm float-end']) }}
        <a href="{{ url()->previous() }}" class="btn btn-sm">{{__('cancelar')}}</a>
    </div>

</form> 
@endsection
