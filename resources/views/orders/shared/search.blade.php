
<form action="{{ $route }}" method="GET" class="row mb-3">
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="basic-addon1">{{ __('Order') }} nº</span>
          {{ Form::text('sequence', request()->input('sequence'), ['class' => 'form-control']) }}
        </div>
    </div>
    @if (!(isset($exclude) && in_array('departments', $exclude)))
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-area">{{ __('Department') }}</span>
          {{ Form::select('department', [null => __('selecciona')] + $departments, request()->input('department'), ['class'=>'form-select']) }}
        </div>
    </div>
    @endif
    @if (!(isset($exclude) && in_array('types', $exclude)))
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-type">{{ __('tipo') }}</span>
          </select>
          {{ Form::select('type', [
              null =>  __('selecciona'),
              \App\Entities\Area::TYPE_EQUIPAMIENTO => \App\Entities\Area::typeName(\App\Entities\Area::TYPE_EQUIPAMIENTO),
              \App\Entities\Area::TYPE_FUNGIBLE => \App\Entities\Area::typeName(\App\Entities\Area::TYPE_FUNGIBLE),
              \App\Entities\Area::TYPE_LANBIDE => \App\Entities\Area::typeName(\App\Entities\Area::TYPE_LANBIDE),
          ], request()->input('type'), ['class'=>'form-select', 'aria-describedby' => 'addon-type']) }}
        </div>
    </div>
    @endif
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-status">{{ __('Status') }}</span>
          {{ Form::select('status', [
              null => __('selecciona'),
              \App\Entities\Order::STATUS_CREATED => \App\Entities\Order::statusName(\App\Entities\Order::STATUS_CREATED),
              \App\Entities\Order::STATUS_PAID => \App\Entities\Order::statusName(\App\Entities\Order::STATUS_PAID),
              \App\Entities\Order::STATUS_RECEIVED => \App\Entities\Order::statusName(\App\Entities\Order::STATUS_RECEIVED),
              \App\Entities\Order::STATUS_MOVED => \App\Entities\Order::statusName(\App\Entities\Order::STATUS_MOVED),
          ], request()->input('status'), ['class'=>'form-select', 'aria-describedby' => 'addon-status']) }}
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
          <button class="btn btn-primary" type="submit" id="button-addon2">
            <span data-feather="search"></span>
          </button>
        </div>
    </div>
</form>
