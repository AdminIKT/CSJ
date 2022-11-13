<button data-bs-toggle="collapse" data-bs-target="#collapseForm" role="button" aria-expanded="false" aria-controls="collapseForm" class="text-decoration-none btn btn-sm btn-outline-secondary btn-filter m-1 ms-0 collapsed">
    <i class="bx bx-xs bx-search-alt-2"></i> {{ __('Filter') }}
</button>
<form id="collapseForm" action="{{ $route }}" method="GET" class="row collapse mb-3">
    @if (!(isset($exclude) && in_array('status', $exclude)))
    <div class="col">
        <div class="input-group input-group-sm mb-3">
        <span class="input-group-text" id="addon-status">{{ __('Status') }}</span>
        {{ Form::select('status', [
            null => __('selecciona'),
            \App\Entities\Account::STATUS_INACTIVE => \App\Entities\Account::statusName(\App\Entities\Account::STATUS_INACTIVE),
            \App\Entities\Account::STATUS_ACTIVE => \App\Entities\Account::statusName(\App\Entities\Account::STATUS_ACTIVE),
        ], request()->input('status'), ['class'=>'form-select', 'aria-describedby' => 'addon-status']) }}
        </div>
    </div>
    @endif
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="basic-addon1">{{ __('Name') }}</span>
          {{ Form::text('name', request()->input('name'), ['class' => 'form-control']) }}
        </div>
    </div>
    @if (isset($areas))
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
              \App\Entities\Account::TYPE_EQUIPAMIENTO => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_EQUIPAMIENTO),
              \App\Entities\Account::TYPE_FUNGIBLE => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_FUNGIBLE),
              \App\Entities\Account::TYPE_LANBIDE => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_LANBIDE),
              \App\Entities\Account::TYPE_OTHER => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_OTHER),
          ], request()->input('type'), ['class'=>'form-select', 'aria-describedby' => 'addon-type']) }}
        </div>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <span class="input-group-text" id="basic-addon1">{{ __('Real credit') }}</span>
                  <div class="input-group-prepend">
                      {{ Form::select('creditOp', [
                        ">=" => ">=",
                        "="  => "==",
                        "<=" => "<=",
                      ], request()->input('creditOp'), ['class'=>'form-select form-select-sm', 'aria-describedby' => 'addon-type']) }}
                  </div>
                  {{ Form::number('credit', request()->input('credit'), ['class' => 'form-control', 'step' => '0.01', 'min' => 0]) }}
                  <span class="input-group-text">€</span>
                </div>
            </div>
            <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <span class="input-group-text" id="basic-addon1">{{ __('Compromised credit') }}</span>
                  <div class="input-group-prepend">
                      {{ Form::select('compromisedOp', [
                        ">=" => ">=",
                        "="  => "==",
                        "<=" => "<=",
                      ], request()->input('compromisedOp'), ['class'=>'form-select form-select-sm', 'aria-describedby' => 'addon-type']) }}
                  </div>
                  {{ Form::number('compromised', request()->input('compromised'), ['class' => 'form-control', 'step' => '0.01', 'min' => 0]) }}
                  <span class="input-group-text">€</span>
                </div>
            </div>
            <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <span class="input-group-text" id="basic-addon1">{{ __('Available credit') }}</span>
                  <div class="input-group-prepend">
                      {{ Form::select('availableOp', [
                        ">=" => ">=",
                        "="  => "==",
                        "<=" => "<=",
                      ], request()->input('availableOp'), ['class'=>'form-select form-select-sm', 'aria-describedby' => 'addon-type']) }}
                  </div>
                  {{ Form::number('available', request()->input('available'), ['class' => 'form-control', 'step' => '0.01', 'min' => 0]) }}
                  <span class="input-group-text">€</span>
                  <button class="btn btn-primary" type="submit" id="button-addon2">
                    <span data-feather="search"></span>
                  </button>
                </div>
            </div>
        </div>
    </div>
</form>

