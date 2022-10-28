@extends('new_layout')
@section('title'){{ __('New invoiceCharge') }}@endsection

@section('content')

    {{ Form::open([
        'route' => ['invoiceCharges.store'], 
        'method' => 'POST', 
        'class' => 'row',
        'novalidate' => true,
        ]) 
    }}

    <div class="col-md-6 mb-3">
        {{ Form::label('credit', __('importe'), ['class' => 'form-label']) }}
        <div class="input-group input-group-sm">
            {{ Form::number('credit', null, ['step' => '0.01', 'min' => 0, 'class' => 'form-control' . ($errors->has('credit') ? ' is-invalid':'') ]) }}
            <span class="input-group-text">€</span>
            @if ($errors->has('credit'))
               <div class="invalid-feedback">{!! $errors->first('credit') !!}</div>
            @endif
        </div>
        <div class="col-md-6 mb-3">
        {{ Form::label('invoice', __('Invoice nº'), ['class' => 'form-label']) }}
        {{ Form::text('invoice', null, ['class' => 'form-control form-control-sm' . ($errors->has('invoice') ? ' is-invalid':'')]) }}
        @if ($errors->has('invoice'))
           <div class="invalid-feedback">{!! $errors->first('invoice') !!}</div>
        @endif
       
        {{ Form::label('invoiceDate', __('Invoice date'), ['class' => 'form-label']) }}
        
        <!--{{ Form::date('invoiceDate', old('invoiceDate', now()), ['class' => 'form-control form-control-sm' . ($errors->has('invoiceDate') ? ' is-invalid':'')]) }}
        @if ($errors->has('invoiceDate'))
           <div class="invalid-feedback">{!! $errors->first('invoiceDate') !!}</div>
        @endif-->
        {{ Form::date("invoiceDate", old('invoiceDate', now()), ['class' => 'form-control form-control-sm', ($errors->has('invoiceDate') ? ' is-invalid':'')]) }}
        @if ($errors->has('invoiceDate'))
           <div class="invalid-feedback">{!! $errors->first('invoiceDate') !!}</div>
        @endif
    </div>
    </div>

    <div class="col-md-6 mb-3">
        {{ Form::label('detail', __('Description'), ['class' => 'form-label']) }}
        {{ Form::textarea('detail', null, ['class' => 'form-control form-control-sm' . ($errors->has('detail') ? ' is-invalid':''), 'rows' => 4]) }}
        @if ($errors->has('detail'))
           <div class="invalid-feedback">{!! $errors->first('detail') !!}</div>
        @endif
    </div>

    <div class="col-md-12">
        <a href="{{ url()->previous() }}" class="btn btn-sm">{{__('cancelar')}}</a>
        {{ Form::submit(__('guardar'), ['class' => 'btn btn-sm btn-primary float-end']) }}
    </div>

    {{ Form::close() }}

@endsection
