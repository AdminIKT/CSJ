@extends('sj_layout')
@section('title'){{ __('New charge (:charge)', ['charge' => App\Entities\InvoiceCharge::typeName(\App\Entities\InvoiceCharge::TYPE_INVOICED)]) }}@endsection

@section('content')

    {{ Form::open([
        'route' => ['invoiceCharges.store'], 
        'method' => 'POST', 
        'class' => 'row',
        'novalidate' => true,
        ]) 
    }}

    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6 mb-3">
                {{ Form::label('credit', __('importe'), ['class' => 'form-label']) }}
                <div class="input-group input-group-sm mb-3">
                    {{ Form::number('credit', old('credit'), ['step' => '0.01', 'min' => 0, 'class' => 'form-control' . ($errors->has('credit') ? ' is-invalid':'') ]) }}
                    <span class="input-group-text">€</span>
                    @if ($errors->has('credit'))
                       <div class="invalid-feedback">{!! $errors->first('credit') !!}</div>
                    @endif
                </div>
            </div>
            <div class="col-md-6 mb-3">
                {{ Form::label('hzyear', __('Hezkuntza code'), ['class' => 'form-label']) }}
                <div class="input-group input-group-sm">
                    {{ Form::text("hzyear", old('hzyear'), [
                        'class'       => 'form-control form-control-sm' . ($errors->has("hzyear") ? ' is-invalid':''),
                        'placeholder' => __('Year'),
                    ]) }}
                    <span class="input-group-text">-</span>
                    {{ Form::text("hzentry", old('hzentry'), [
                        'class'       => 'form-control form-control-sm' . ($errors->has("hzentry") ? ' is-invalid':''),
                        'placeholder' => __('Entry'),
                    ]) }}
                    @if ($errors->has("hzyear"))
                        <div class="invalid-feedback">{!! $errors->first("hzyear") !!}</div>
                    @endif
                    @if ($errors->has("hzentry"))
                        <div class="invalid-feedback">{!! $errors->first("hzentry") !!}</div>
                    @endif
                </div>
            </div>
            <div class="col-md-6 mb-3">
                {{ Form::label('invoice', __('Invoice nº'), ['class' => 'form-label']) }}
                {{ Form::text('invoice', null, ['class' => 'form-control form-control-sm' . ($errors->has('invoice') ? ' is-invalid':'')]) }}
                @if ($errors->has('invoice'))
                   <div class="invalid-feedback">{!! $errors->first('invoice') !!}</div>
                @endif
            </div>
            <div class="col-md-6 mb-3">
                {{ Form::label('invoiceDate', __('Invoice date'), ['class' => 'form-label']) }}
                {{ Form::date("invoiceDate", old('invoiceDate', now()), ['class' => 'form-control form-control-sm', ($errors->has('invoiceDate') ? ' is-invalid':'')]) }}
                @if ($errors->has('invoiceDate'))
                   <div class="invalid-feedback">{!! $errors->first('invoiceDate') !!}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        {{ Form::label('detail', __('Description'), ['class' => 'form-label']) }}
        {{ Form::textarea('detail', null, [
            'class' => 'form-control form-control-sm' . ($errors->has('detail') ? ' is-invalid':''), 
            'rows'  => 5,
        ]) }}
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
