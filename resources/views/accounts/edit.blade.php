@extends('sj_layout')
@section('title')
@if ($entity->getId()) 
    {{ __('Edit account :name', ['name' => $entity->getSerial()]) }} 
@else 
    {{ __('New account') }} 
@endif
@endsection

@section('content')
<form action="{{ $route }}" method="POST" class="row" novalidate>
    @csrf
    {{ method_field($method) }}

    <div class="col-3 mb-3">
        {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
        <div class="input-group input-group-sm">
            <span class="input-group-text">
                <i class="cbg {!! $entity->getStatusColor() !!}"></i>
            </span>
            {{ Form::select('status', [
                null => __('Selecciona'),
                \App\Entities\Account::STATUS_INACTIVE => \App\Entities\Account::statusName(\App\Entities\Account::STATUS_INACTIVE),
                \App\Entities\Account::STATUS_ACTIVE => \App\Entities\Account::statusName(\App\Entities\Account::STATUS_ACTIVE),
            ], old('status', $entity->getStatus()), ['class'=>'form-select form-select-sm' . ($errors->has('status') ? ' is-invalid':'')], [null => ['disabled' => true]]) }}
            @if ($errors->has('status'))
               <div class="invalid-feedback">{!! $errors->first('status') !!}</div>
            @endif
        </div>
    </div>

    <div class="col-3 mb-3">
        {{ Form::label('type', __('tipo'), ['class' => 'form-label']) }}
        {{ Form::text('type', $entity->getTypeName(), ['class' => 'form-control form-control-sm', 'disabled' => true]) }}
    </div>

    <div class="col-6 mb-3">
        {{ Form::label('lcode', __('Code'), ['class' => 'form-label']) }}
        {{ Form::text('lcode', $entity->getLCode(), ['class' => 'form-control form-control-sm', 'disabled' => true ]) }}
    </div>

    <div class="col-3 mb-3">
        {{ Form::label('acronym', __('acronimo'), ['class' => 'form-label']) }}
        <div class="input-group input-group-sm">
            {{ Form::text('acronym', old('acronym', $entity->getAcronym()), ['class' => 'form-control', 'disabled' => true]) }}
            <span class="input-group-text">-</span>
            <span class="input-group-text">{{ $entity->getType() }}</span>
            @if ($entity->getLCode())
            <span class="input-group-text">-</span>
            <span class="input-group-text">{{ $entity->getLCode() }}</span>
            @endif
        </div>
        @if ($errors->has('acronym'))
           <div class="invalid-feedback">{!! $errors->first('acronym') !!}</div>
        @endif
    </div>
    
    <div class="col-9 mb-3">
        {{ Form::label('name', __('nombre'), ['class' => 'form-label']) }}
        {{ Form::text('name', old('name', $entity->getName()), ['class' => 'form-control form-control-sm' . ($errors->has('name') ? ' is-invalid':'')]) }}
        @if ($errors->has('name'))
           <div class="invalid-feedback">{!! $errors->first('name') !!}</div>
        @endif
    </div>
    <div class="col-12 mb-3">
        {{ Form::label('detail', __('detalle'), ['class' => 'form-label mt-3']) }}
        {{ Form::textarea('detail', old('detail', $entity->getDetail()), ['class' => 'form-control form-control-sm', 'rows' => 2]) }}
        @if ($errors->has('detail'))
           <div class="invalid-feedback">{!! $errors->first('detail') !!}</div>
        @endif
    </div>

    {{ Form::label('users[]', __('Users'), ['class' => 'form-label']) }}
    <fieldset class="collection-container d-flex flex-wrap" 
             data-prototype='@include("accounts.shared.form_user", ["index" => "__NAME__"])'>
        @foreach (old('users', Arr::pluck($entity->getUsers(), 'id')) as $i => $user)
            @include('accounts.shared.form_user', ['index' => $i, 'id' => $user])
        @endforeach
        <button type="button" class="btn btn-sm btn-outline-secondary mb-3" onclick="addToCollection(this)">
            <i class="bx bx-plus"></i> {{__('New user')}}
        </button>
    </fieldset>

    <hr>
    <div>
        {{ Form::submit(__('guardar'), ['class' => 'btn btn-primary btn-sm float-end']) }}
        <a href="{{ url()->previous() }}" class="btn btn-sm">
            {{ __('cancelar')}}
        </a>
    </div>

</form>
@endsection
