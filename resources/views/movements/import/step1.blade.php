@extends('sj_layout')
@section('title')
    {{ __('Import charges (:charge)', ['charge' => \App\Entities\InvoiceCharge::typeName($type)]) }}
@endsection
@section('content')

    {{ Form::open([
        'route' => [$route, ['charge' => $type]], 
        'method' => 'POST', 
        'class' => 'row',
        'enctype' => 'multipart/form-data',
        'novalidate' => true,
       ])
    }}
        <div class="col-md-12 mb-3">
            {{ Form::label('file', __('File'), ['class' => 'form-label']) }}
            {{ Form::file("file", ['class' => 'form-control form-control-sm' . ($errors->has('file') ? ' is-invalid':''), 'aria-describedby' => 'fileHelpBlock']) }}
            <div id="emailHelpBlock" class="form-text">
                {{ __("File must be an ':extension' extension with ':cols' cols", [
                    'extension' => implode(', ', \App\Http\Controllers\InvoiceCharge\Imports\AllExtensionsController::getSupportedMimeTypes()),
                    'cols'      => implode(', ', \App\Http\Controllers\InvoiceCharge\Imports\AllExtensionsController::getTypeColumns($type)),
                ]) }}
            </div>
            @if ($errors->has('file'))
                <div class="invalid-feedback">{!! $errors->first('file') !!}</div>
            @endif
        </div>

        <div class="col-md-12">
            <a href="{{ url()->previous() }}" class="btn btn-sm ">{{__('cancelar')}}</a>
            {{ Form::submit(__('guardar'), ['class' => 'btn btn-sm btn-primary float-end']) }}
        </div>

    {{ Form::close() }}
@endsection

