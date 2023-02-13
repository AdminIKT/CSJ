@extends('sj_layout')
@section('title')
    {{ __('Edit order nº :number', ['number' => $entity->getSequence()]) }}
@endsection
@section('content')
    {{ Form::open([
        'route' => ['orders.update', ['order' => $entity->getId()]], 
        'method' => 'PUT', 
        'enctype' => 'multipart/form-data',
        'novalidate' => true,
       ])
    }}
    @csrf
    {{ method_field('PUT') }}

    <div class="row">
        <div class="col mb-3">
            {{ Form::label('receiveIn', __('Receive in'), ['class' => 'form-label']) }}
            {{ Form::select('receiveIn', [null => __('selecciona'), \App\Entities\Order::RECEIVE_IN_DEPARTMENT => App\Entities\Order::receiveInName(\App\Entities\Order::RECEIVE_IN_DEPARTMENT), \App\Entities\Order::RECEIVE_IN_RECEPTION => \App\Entities\Order::receiveInName(\App\Entities\Order::RECEIVE_IN_RECEPTION)], old('receiveIn', $entity->getReceiveIn()), ['class' => 'form-select form-select-sm' . ($errors->has('receiveIn') ? ' is-invalid':'')], [null => ['disabled' => true]]) }}
            @if ($errors->has('receiveIn'))
               <div class="invalid-feedback">{!! $errors->first('receiveIn') !!}</div>
            @endif
        </div>

        <div class="col mb-3">
            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
            {{ Form::date("date", old('date', $entity->getDate()), ['class' => 'form-control form-control-sm']) }}
        </div>
    </div>

    <div class="row">
        <div class="col mb-3">
            {{ Form::label('detail', __('detalle'), ['class' => 'form-label mt-3']) }}
            {{ Form::textarea('detail', old('detail', $entity->getDetail()), ['class' => 'form-control form-control-sm', 'rows' => 2]) }}
            @if ($errors->has('detail'))
               <div class="invalid-feedback">{!! $errors->first('detail') !!}</div>
            @endif
        </div>
    </div>

    <fieldset class="mb-3 collection-container" 
             data-prototype='@include("subaccounts.orders.shared.form_product", ["index" => "__NAME__", "product" => []])'>
        <legend>{{__('elementos')}}</legend>
        @foreach (old('products', $entity->getProducts()->map(function($item) { return $item->toArray(); })) as $i => $product)
            @include("subaccounts.orders.shared.form_product", ["index" => $i, 'product' => $product])
        @endforeach
        <button type="button" class="btn btn-sm btn-outline-primary float-end" onclick="addToCollection(this)">
            <i class="bx bx-plus"></i> {{__('nuevo_elemento')}}
        </button>
    </fieldset>

    <hr>
    <div class="col-md-12">
        <button type="submit" class="btn btn-sm btn-primary float-end">
            <i class='bx bxs-save'></i> {{ __('guardar') }}
        </button>
        <a href="{{ url()->previous() }}" class="btn btn-sm">
            <i class="bx bx-x"></i> {{__('cancelar')}}
        </a>
    </div>

    {{ Form::close() }}
@endsection
