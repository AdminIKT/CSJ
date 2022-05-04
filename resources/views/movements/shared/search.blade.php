
<form action="{{ $route }}" method="GET" class="row mb-3">
    <div class="col-12">
        <div class="row">
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
            <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <span class="input-group-text" id="addon-type">{{ __('Type') }}</span>
                  {{ Form::select('type', [
                        null   => __('Selecciona'),
                        App\Entities\Charge::TYPE_CASH => App\Entities\Charge::typeName(App\Entities\Charge::TYPE_CASH),
                        App\Entities\Charge::TYPE_OTHER => App\Entities\Charge::typeName(App\Entities\Charge::TYPE_OTHER),
                        App\Entities\Assignment::TYPE_ANUAL => App\Entities\Assignment::typeName(App\Entities\Assignment::TYPE_ANUAL),
                        App\Entities\Assignment::TYPE_EXTRAORDINARY => App\Entities\Assignment::typeName(App\Entities\Assignment::TYPE_EXTRAORDINARY),
                        App\Entities\Assignment::TYPE_OTHER => App\Entities\Assignment::typeName(App\Entities\Assignment::TYPE_OTHER),
                    ], request()->input('type'), ['class'=>'form-select', 'aria-describedby' => 'addon-type']) }}
                </div>
            </div>
            @if (!(isset($exclude) && in_array('accounts', $exclude)))
            <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <span class="input-group-text" id="addon-account">{{ __('Account') }}</span>
                  {{ Form::select('account', [null => __('Selecciona')] + $accounts, request()->input('account'), ['class'=>'form-select', 'aria-describedby' => 'addon-account']) }}
                </div>
            </div>
            @endif
            @if (!(isset($exclude) && in_array('areas', $exclude)))
            <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <span class="input-group-text" id="addon-area">{{ __('Area') }}</span>
                  {{ Form::select('area', [null => __('Selecciona')] + $areas, request()->input('area'), ['class'=>'form-select', 'aria-describedby' => 'addon-area']) }}
                </div>
            </div>
            @endif
        </div>
    </div>
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
                "="  => "==",
                "<=" => "<=",
              ], request()->input('operator'), ['class'=>'form-select form-select-sm', 'aria-describedby' => 'addon-type']) }}
          </div>
          {{ Form::number('credit', request()->input('credit'), ['class' => 'form-control', 'step' => '0.01', 'min' => 0]) }}
          <span class="input-group-text">€</span>
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
            <span class="input-group-text" id="addon-status">{{ __('Pagination') }}</span>
            {{ Form::select('perPage', [
                null =>  __('All'),
                5 => 5,
                10 => 10,
                20 => 20,
                50 => 50,
            ], request()->input('perPage', $perPage), ['class'=>'form-select form-select-sm']) }}
          <button class="btn btn-outline-primary" type="submit" id="button-addon2">
            <span data-feather="search"></span>
          </button>
        </div>
    </div>
</form>
