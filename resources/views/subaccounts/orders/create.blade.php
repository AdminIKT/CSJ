@extends('sj_layout')
@section('title'){{ __('New order') }}@endsection
@section('btn-toolbar')
    @can('create', 'App\Entities\Supplier')
    <a href="{{ route('suppliers.create', ['destination' => request()->url()]) }}" class="btn btn-sm btn-outline-secondary m-1 ms-0">
        <span data-feather="shopping-bag"></span> {{ __('New supplier') }} 
    </a>
    @endcan
@endsection
@section('content')
    {{ Form::open([
        'route' => ['subaccounts.orders.store', ['subaccount' => $subaccount->getId()]], 
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
            {{ __("Real credit: :credit € - compromised: :compromised € = available :available €", ['credit' => number_format($subaccount->getCredit(), 2, ",", "."), 'compromised' => number_format($subaccount->getCompromisedCredit(), 2, ",", "."), 'available' => number_format($subaccount->getAvailableCredit(), 2, ",", ".")]) }}
        </div>

        <div class="mb-3">
        {{ Form::label("supplier", __('proveedor'), ['class' => 'form-label']) }}
        {{ Form::select("supplier", [null => __('selecciona')] + $suppliers, old("supplier", null), ["class" => "form-select form-select-sm" . ($errors->has("supplier") ? " is-invalid":"")], [null => ["disabled" => true]] + $disableds) }}
        @if ($errors->has("supplier"))
           <div class="invalid-feedback">{!! $errors->first("supplier") !!}</div>
        @endif
        </div>
        @can('viewAny', App\Entities\Order::class)
        {{ Form::checkbox("custom", true, old('custom'), ['id' => 'custom', 'class' => 'form-check-input', 'onchange' => 'sequences.displayCustom()']) }}         
        {{ Form::label('custom', __('intercalar'), ['class' => 'form-label']) }}
        <small class="text-muted">{{ __('Last order')}}: 
        @if ($lastest->count()) 
        {{ $lastest->first()->getSequence() }} {{ Carbon\Carbon::parse($lastest->first()->getDate())->diffForHumans() }} 
        @else {{ __('None') }}  @endif
        </small>
        <div id="custom-fields" class="row border rounded py-2 d-none">
            <div class="col-md-12 text-center text-muted small pb-2" id="sequence-alert"></div>
            <div class="col-md-4">
                {{ Form::label('previous', __('Select previous'), ['class' => 'form-label']) }}
                {{ Form::select('previous', [null => __('selecciona')] + Arr::pluck($lastest->items(), 'sequence', 'id'), old('previous', null), ['id' => 'previous', 'class'=>'form-select form-select-sm'. ($errors->has('previous') ? ' is-invalid':''), 'disabled' => true, 'onchange' => 'sequences.change()'], [null => ['disabled' => true]]) }}
                @if ($errors->has('previous'))
                   <div class="invalid-feedback">{!! $errors->first('previous') !!}</div>
                @endif
            </div>
            <div class="col-md-4">
                {{ Form::label('sequence', __('Current sequence'), ['class' => 'form-label']) }}
                {{ Form::text("sequence", old('sequence', null), ['class' => 'form-control form-control-sm' . ($errors->has('sequence') ? ' is-invalid':''), 'disabled' => true]) }}
                @if ($errors->has('sequence'))
                   <div class="invalid-feedback">{!! $errors->first('sequence') !!}</div>
                @endif
            </div>
            <div class="col-md-4">
                {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
                {{ Form::date("date", old('date', now()), ['class' => 'form-control form-control-sm' . ($errors->has('date') ? ' is-invalid':''), 'disabled' => true]) }}
                @if ($errors->has('date'))
                   <div class="invalid-feedback">{!! $errors->first('date') !!}</div>
                @endif
            </div>
        </div>
        @else
        <div class="mb-3">
            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
            <small class="text-muted">{{ __('Last order')}}:
            @if ($lastest->count()) 
            {{ $lastest->first()->getSequence() }} {{ Carbon\Carbon::parse($lastest->first()->getDate())->diffForHumans() }} 
            @else {{ __('None') }}  @endif
            </small>
            {{ Form::date("date", old('date', now()), ['class' => 'form-control form-control-sm', 'disabled' => true]) }}
        </div>
        @endif
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

    <fieldset class="row mb-3 collection-container" 
             data-prototype='@include("subaccounts.orders.shared.form_product", ["index" => "__NAME__", "product" => []])'>
        <legend>{{__('elementos')}}</legend>
        @foreach (old('products', []) as $i => $product)
            @include("subaccounts.orders.shared.form_product", ["index" => $i, 'product' => $product])
        @endforeach
    </fieldset>

    <div class="col-md-12">
        <button type="submit" class="btn btn-sm btn-primary float-end">
            <i class='bx bxs-save'></i> {{ __('guardar') }}
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary float-end mx-2" onclick="addToCollection()">
            <i class="bx bx-plus"></i> {{__('nuevo_elemento')}}
        </button>
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary">
            <i class='bx bx-x'></i> {{__('cancelar')}}
        </a>
    </div>

    {{ Form::close() }}

@endsection

@section('scripts')
    <script src="{{ asset('js/custom/form-collections.js') }}"></script>
    <script src="{{ asset('js/custom/order-intercalate.js') }}"></script>

    <script>
        var lastest = @php echo json_encode(array_combine(
                $lastest->map(function($e) { return $e->getId(); })->toArray(),
                $lastest->items()
            )); @endphp;

        let sequences = new Sequences(lastest);

        $(document).ready(function() {
            sequences.displayCustom();
        });
    </script>

@endsection
