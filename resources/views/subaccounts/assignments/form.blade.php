@extends('sj_layout')
@section('title')
@if ($entity->getId()) 
    {{ __('Edit assignment') }} 
@else 
    {{ __('New assignment') }} 
@endif
@endsection
 
@section('content')
<form action="{{ route('subaccounts.assignments.store', ['subaccount' => $entity->getSubaccount()->getId()]) }}" method="POST" class="row" novalidate>
    @csrf
   
    <div class="col-md-6 mb-3">
        {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}
        {{ Form::select('type', [
            null => __('selecciona'),
            \App\Entities\Assignment::TYPE_ANUAL => \App\Entities\Assignment::typeName(\App\Entities\Assignment::TYPE_ANUAL),
            \App\Entities\Assignment::TYPE_EXTRAORDINARY => \App\Entities\Assignment::typeName(\App\Entities\Assignment::TYPE_EXTRAORDINARY),
            \App\Entities\Assignment::TYPE_OTHER => \App\Entities\Assignment::typeName(\App\Entities\Assignment::TYPE_OTHER),
        ], old('type', $entity->getType()), ['class'=>'form-select form-select-sm' . ($errors->has('type') ? ' is-invalid':'')], [null => ['disabled' => true]]) }}
        @if ($errors->has('type'))
           <div class="invalid-feedback">{!! $errors->first('type') !!}</div>
        @endif
    </div>
    <div class="col-md-6 mb-3">
        {{ Form::label('credit', __('importe'), ['class' => 'form-label']) }}
        <div class="input-group input-group-sm">
        {{ Form::number('credit', old('credit', $entity->getCredit()), ['class' => 'form-control' . ($errors->has('credit') ? ' is-invalid':''), 'step' => '0.01', 'min' => 0]) }}
        <span class="input-group-text">€</span>
        @if ($errors->has('credit'))
           <div class="invalid-feedback">{!! $errors->first('credit') !!}</div>
        @endif
        </div>
    </div>
    <div class="col-md-12 mb-3">
        {{ Form::label('detail', __('Detail'), ['class' => 'form-label mt-3']) }}
        {{ Form::textarea('detail', old('detail', null), ['class' => 'form-control form-control-sm', 'rows' => 2]) }}
        @if ($errors->has('detail'))
           <div class="invalid-feedback">{!! $errors->first('detail') !!}</div>
        @endif
    </div>
    <div class="col-md-12 mb-3">
        {{ Form::submit(__('guardar'), ['class' => 'btn btn-sm btn-primary float-end']) }}
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-default">{{__('cancelar')}}</a>
    </div>
    @if (isset($dst))
        {{ Form::hidden('destination', $dst ?? '') }}
    @endif

    {{ Form::close() }}

@endsection
