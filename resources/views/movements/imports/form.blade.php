@extends('new_layout')
@section('title')
    {{ __('Import movements') }}
@endsection
@section('content')

    {{ Form::open([
        'route' => ['imports.list'], 
        'method' => 'POST', 
        'class' => 'row',
        'enctype' => 'multipart/form-data',
        'novalidate' => true,
       ])
    }}
        <div class="col-md-12 mb-3">
            {{ Form::label('file', 'File', ['class' => 'form-label']) }}
            {{ Form::file("file", ['class' => 'form-control form-control-sm' . ($errors->has('file') ? ' is-invalid':''), 'aria-describedby' => 'fileHelpBlock']) }}
            <div id="emailHelpBlock" class="form-text">
                {{ __("File must be an ':extension' extension", ['extension' => 'xlsx']) }}
            </div>
            @if ($errors->has('file'))
                <div class="invalid-feedback">{!! $errors->first('file') !!}</div>
            @endif
        </div>

        <div class="col-md-12">
            <a href="{{route('movements.index')}}" class="btn btn-sm ">Cancel</a>
            {{ Form::submit('Save', ['class' => 'btn btn-sm btn-primary float-end']) }}
        </div>

    {{ Form::close() }}
@endsection

