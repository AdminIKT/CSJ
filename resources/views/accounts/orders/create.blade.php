@extends('new_layout')
@section('title'){{ __('New order') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('suppliers.create', ['destination' => request()->url()]) }}" class="btn btn-sm btn-outline-secondary">
        <span data-feather="shopping-bag"></span> {{ __('New supplier') }} 
    </a>
@endsection
@section('content')

    {{ Form::open([
        'route' => ['accounts.orders.store', ['account' => $account->getId()]], 
        'method' => 'POST', 
        'class' => 'row',
        'enctype' => 'multipart/form-data',
        'novalidate' => true,
       ])
    }}

    <div class="col-md-6 mb-3">
        {{ Form::label('credit', __("Estimated credit"), ['class' => 'form-label']) }}
        <div class="input-group input-group-sm">
            {{ Form::number('estimatedCredit', old('estimatedCredit', $entity->getEstimatedCredit()), ['step' => '0.01', 'min' => 0, 'class' => 'form-control' . ($errors->has('estimatedCredit') ? ' is-invalid':'') ]) }}
            <span class="input-group-text">€</span>
            @if ($errors->has('estimatedCredit'))
               <div class="invalid-feedback">{!! $errors->first('estimatedCredit') !!}</div>
            @endif
        </div>
        <div id="emailHelpBlock" class="form-text mb-3">
            {{ __("Real credit: :credit € - compromised: :compromised € = available :available €", ['credit' => number_format($account->getCredit(), 2, ",", "."), 'compromised' => number_format($account->getCompromisedCredit(), 2, ",", "."), 'available' => number_format($account->getAvailableCredit(), 2, ",", ".")]) }}
        </div>

        <div class="mb-3">
        {{ Form::label("supplier", __('proveedor'), ['class' => 'form-label']) }}
        {{ Form::select("supplier", [null => __('selecciona')] + $suppliers, old("supplier", null), ["class" => "form-select form-select-sm" . ($errors->has("supplier") ? " is-invalid":"")], [null => ["disabled" => true]] + $disableds) }}
        @if ($errors->has("supplier"))
           <div class="invalid-feedback">{!! $errors->first("supplier") !!}</div>
        @endif
        </div>

        {{ Form::label('custom', __('intercalar'), ['class' => 'form-label']) }}
        {{ Form::checkbox("custom", true, old('custom'), ['class' => 'form-check-input', 'onchange' => 'displayCustom(this)']) }}         
        <div id="custom-fields" class="row d-none">
            <div class="col-md-12 text-center small" id="sequence-alert"></div>
            <div class="col-md-4">
                {{ Form::label('previous', 'Select previous', ['class' => 'form-label']) }}
                {{ Form::select('previous', array_merge([null => '--Select one--'], $account->getOrders()->map(function($e) {return $e->getSequence(); })->toArray()), old('previous', null), ['class'=>'form-select form-select-sm', 'disabled' => true, 'onchange' => 'selectSequence(this)'], [null => ['disabled' => true]]) }}
            </div>
            <div class="col-md-4">
                {{ Form::label('sequence', 'Current sequence', ['class' => 'form-label']) }}
                {{ Form::text("sequence", old('sequence', null), ['class' => 'form-control form-control-sm' . ($errors->has('sequence') ? ' is-invalid':''), 'disabled' => true]) }}
                @if ($errors->has('sequence'))
                   <div class="invalid-feedback">{!! $errors->first('sequence') !!}</div>
                @endif
            </div>
            <div class="col-md-4">
                {{ Form::label('date', 'Date', ['class' => 'form-label']) }}
                {{ Form::date("date", old('date', now()), ['class' => 'form-control form-control-sm', 'disabled' => true]) }}
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        {{ Form::label('estimated', __('presupuesto'), ['class' => 'form-label']) }}
        {{ Form::file("estimated", ['class' => 'form-control form-control-sm' . ($errors->has('estimated') ? ' is-invalid':'')]) }}
        @if ($errors->has('estimated'))
           <div class="invalid-feedback">{!! $errors->first('estimated') !!}</div>
        @endif
        {{ Form::label('receiveIn', __('Receive in'), ['class' => 'form-label mt-3']) }}
        {{ Form::select('receiveIn', [null => __('selecciona'), \App\Entities\Order::RECEIVE_IN_DEPARTMENT => App\Entities\Order::receiveInName(\App\Entities\Order::RECEIVE_IN_DEPARTMENT), \App\Entities\Order::RECEIVE_IN_RECEPTION => \App\Entities\Order::receiveInName(\App\Entities\Order::RECEIVE_IN_RECEPTION)], old('receiveIn'), ['class' => 'form-select form-select-sm' . ($errors->has('receiveIn') ? ' is-invalid':'')], [null => ['disabled' => true]]) }}
        @if ($errors->has('receiveIn'))
           <div class="invalid-feedback">{!! $errors->first('receiveIn') !!}</div>
        @endif
        {{ Form::label('detail', __('detalle'), ['class' => 'form-label mt-3']) }}
        {{ Form::textarea('detail', old('detail', null), ['class' => 'form-control form-control-sm', 'rows' => 2]) }}
        @if ($errors->has('detail'))
           <div class="invalid-feedback">{!! $errors->first('detail') !!}</div>
        @endif
    </div>

    <fieldset class="col-md-12 mb-3 collection-container" 
             data-prototype='@include("accounts.orders.shared.form_product", ["index" => "__NAME__"])'>
        <legend>{{__('elementos')}}</legend>
        @foreach (old('products', [[]]) as $i => $product)
            @include("accounts.orders.shared.form_product", ["index" => $i, 'product' => $product])
        @endforeach
    </fieldset>

    <div class="col-md-12">
        {{ Form::submit(__('guardar'), ['class' => 'btn btn-sm btn-primary float-end']) }}
        <button type="button" class="btn btn-sm btn-outline-primary float-end mx-2" onclick="addToCollection()">
            <span data-thead="plus"></span> {{__('nuevo_elemento')}}
        </button>
        <a href="{{ url()->previous() }}" class="btn btn-sm">{{__('cancelar')}}</a>
    </div>

    {{ Form::close() }}

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script>
        function addToCollection(btn) {
            var container = $('.collection-container');
            var count = container.children().length;
            var proto = container.data('prototype').replace(/__NAME__/g, count);
            container.append(proto);
        }

        function rmCollection(btn) {
            btn.closest('div').remove();
        }

        function displayCustom(input) {
            if ($(input).prop('checked')) {
                $('#custom-fields').removeClass('d-none');
                $('#custom-fields :input').each(function() {
                    $(this).attr('disabled', false);
                });
            }
            else {
                $('#custom-fields').addClass('d-none');
                $('#custom-fields :input').each(function() {
                    $(this).attr('disabled', true)
                           //.val(null)
                           ;
                });
            } 
        }

        var sequence = @php echo json_encode($account->getOrders()->toArray()); @endphp;
        function selectSequence(input) {
            var index = $(input).val();
            var prev = sequence[index];
            $('#sequence:input').val(prev.sequence + "-1");
            $('#date:input').val(prev.date);
            if (index < sequence.length-1) var next = sequence[index++].date;
            else var next = new Date(); 
            var msg = "Date must be between " + prev.date + " and " + next;
            $('#sequence-alert').html(msg);
        }

        $(document).ready(function() {
            displayCustom($('#custom:input'));
        });
    </script>
@endsection
