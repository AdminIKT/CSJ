@extends('new_layout')
@section('title')
@if ($entity->getId()) 
    {{ __('Edit assignment') }} 
@else 
    {{ __('New assignment') }} 
@endif
@endsection
 
@section('content')
<form action="{{ route('assignments.store') }}" method="POST" class="row" novalidate>
    @csrf
   
    <div class="col-md-4 mb-3">
        {{ Form::label('area', __('Area'), ['class' => 'form-label']) }}
        {{ Form::select('area', [null => 'Select one'] + $areas, old('area', $entity->getArea() ? $entity->getArea()->getId() : null), ['class'=>'form-select form-select-sm' . ($errors->has('area') ? ' is-invalid':''), 'aria-describedby' => 'addon-area'], [null => ['disabled' => true]]) }}
        @if ($errors->has('area'))
           <div class="invalid-feedback">{!! $errors->first('area') !!}</div>
        @endif
    </div>

    <div class="col-md-4 mb-3">
        {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}
        {{ Form::select('type', [
            null => 'Select one',
            \App\Entities\Assignment::TYPE_ANUAL => \App\Entities\Assignment::typeName(\App\Entities\Assignment::TYPE_ANUAL),
            \App\Entities\Assignment::TYPE_EXTRAORDINARY => \App\Entities\Assignment::typeName(\App\Entities\Assignment::TYPE_EXTRAORDINARY),
        ], old('type', $entity->getType()), ['class'=>'form-select form-select-sm' . ($errors->has('type') ? ' is-invalid':'')], [null => ['disabled' => true]]) }}
        @if ($errors->has('type'))
           <div class="invalid-feedback">{!! $errors->first('type') !!}</div>
        @endif
    </div>
    <div class="col-md-4 mb-3">
        {{ Form::label('credit', __('Credit'), ['class' => 'form-label']) }}
        <div class="input-group input-group-sm">
        {{ Form::number('credit', old('credit', $entity->getCredit()), ['class' => 'form-control' . ($errors->has('credit') ? ' is-invalid':''), 'step' => '0.01', 'min' => 0]) }}
        <span class="input-group-text">€</span>
        </div>
        @if ($errors->has('credit'))
           <div class="invalid-feedback">{!! $errors->first('credit') !!}</div>
        @endif
    </div>
    <div class="col-md-12 mb-3">
        {{ Form::label('detail', __('Detail'), ['class' => 'form-label mt-3']) }}
        {{ Form::textarea('detail', old('detail', null), ['class' => 'form-control form-control-sm', 'rows' => 2]) }}
        @if ($errors->has('detail'))
           <div class="invalid-feedback">{!! $errors->first('detail') !!}</div>
        @endif
    </div>
    <div class="col-md-12 mb-3">
        {{ Form::submit('Save', ['class' => 'btn btn-sm btn-primary float-end']) }}
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-default">Cancel</a>
    </div>
    @if (isset($dst))
        {{ Form::hidden('destination', $dst ?? '') }}
    @endif

    {{ Form::close() }}

@endsection
