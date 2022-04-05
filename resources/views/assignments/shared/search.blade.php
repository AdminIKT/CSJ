
<form action="{{ $route }}" method="GET" class="row mb-3">
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-type">{{ __('Year') }}</span>
          </select>
          {{ Form::select('year', [null => __('selecciona')] + $years, request()->input('year'), ['class'=>'form-select', 'aria-describedby' => 'addon-type']) }}
        </div>
    </div>
    @if (!(isset($exclude) && in_array('areas', $exclude)))
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-area">{{ __('Area') }}</span>
          {{ Form::select('area', [null => __('selecciona')] + $areas, request()->input('area'), ['class'=>'form-select', 'aria-describedby' => 'addon-area']) }}
        </div>
    </div>
    @endif
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-type">{{ __('Type') }}</span>
          {{ Form::select('type', [
              null => __('selecciona'),
              \App\Entities\Assignment::TYPE_ANUAL => \App\Entities\Assignment::typeName(\App\Entities\Assignment::TYPE_ANUAL),
              \App\Entities\Assignment::TYPE_EXTRAORDINARY => \App\Entities\Assignment::typeName(\App\Entities\Assignment::TYPE_EXTRAORDINARY),
          ], request()->input('type'), ['class'=>'form-select', 'aria-describedby' => 'addon-type']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="basic-addon1">{{ __('Credit') }}</span>
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
