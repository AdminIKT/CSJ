
<form action="{{ $route }}" method="GET" class="row mb-3">
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-movement">{{ __('Movement') }}</span>
          {{ Form::select('movement', [
                'App\Entities\Movement'   => __('Selecciona'),
                'App\Entities\Assignment' => __('Assignments'),
                'App\Entities\Charge'     => __('Charges'),
            ], request()->input('movement'), ['class'=>'form-select', 'aria-describedby' => 'addon-movement']) }}
        </div>
    </div>
    @if (!(isset($exclude) && in_array('accounts', $exclude)))
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-account">{{ __('Account') }}</span>
          {{ Form::select('account', [null => __('Selecciona')], request()->input('account'), ['class'=>'form-select', 'aria-describedby' => 'addon-account']) }}
        </div>
    </div>
    @endif
    @if (!(isset($exclude) && in_array('areas', $exclude)))
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-area">{{ __('Area') }}</span>
          {{ Form::select('area', [null => __('Selecciona')], request()->input('area'), ['class'=>'form-select', 'aria-describedby' => 'addon-area']) }}
        </div>
    </div>
    @endif
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-from">{{ __('desde') }}</span>
          {{ Form::date('from', request()->input('from'), ['class' => 'form-control', 'aria-describedby' => 'addon-from']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-to">{{ __('hasta') }}</span>
          {{ Form::date('to', request()->input('to'), ['class' => 'form-control', 'aria-describedby' => 'addon-to']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="basic-addon1">{{ __('importe') }}</span>
          <div class="input-group-prepend">
              {{ Form::select('operator', [
                ">=" => ">=",
                "==" => "==",
                "<=" => "<=",
              ], request()->input('operator'), ['class'=>'form-select form-select-sm', 'aria-describedby' => 'addon-type']) }}
          </div>
          {{ Form::number('credit', request()->input('credit'), ['class' => 'form-control', 'step' => '0.01', 'min' => 0]) }}
          <span class="input-group-text">€</span>
          <button class="btn btn-primary" type="submit" id="button-addon2">
            <span data-feather="search"></span>
          </button>
        </div>
    </div>
</form>
