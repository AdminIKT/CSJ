<button data-bs-toggle="collapse" data-bs-target="#collapseForm" role="button" aria-expanded="false" aria-controls="collapseForm" class="text-decoration-none btn btn-sm btn-outline-secondary btn-filter m-1 collapsed">
    <i class="bx bx-xs bx-search-alt-2"></i> {{ __('Filter') }}
</button>
<form id="collapseForm" action="{{ $route }}" method="GET" class="row collapse mb-3">
    <div class="col-12">
        <div class="row">
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
            @if (isset($suppliers))
            <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <span class="input-group-text" id="addon-supplier">{{ __('Supplier') }}</span>
                  {{ Form::select('supplier', [null => __('selecciona')] + $suppliers, request()->input('supplier'), ['class'=>'form-select', 'aria-describedby' => 'addon-supplier']) }}
                </div>
            </div>
            @endif
        </div>
    </div>
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
                        App\Entities\InvoiceCharge::TYPE_CASH => App\Entities\InvoiceCharge::typeName(App\Entities\InvoiceCharge::TYPE_CASH),
                        App\Entities\InvoiceCharge::TYPE_INVOICED => App\Entities\OrderCharge::typeName(App\Entities\InvoiceCharge::TYPE_INVOICED),
                        App\Entities\OrderCharge::TYPE_ORDER_INVOICED => App\Entities\OrderCharge::typeName(App\Entities\OrderCharge::TYPE_ORDER_INVOICED),
                        App\Entities\HzCharge::TYPE_WITHOUT_INVOICED => App\Entities\HzCharge::typeName(App\Entities\HzCharge::TYPE_WITHOUT_INVOICED),
                        App\Entities\Charge::TYPE_OTHER => App\Entities\Charge::typeName(App\Entities\Charge::TYPE_OTHER),
                        App\Entities\Assignment::TYPE_ANUAL => App\Entities\Assignment::typeName(App\Entities\Assignment::TYPE_ANUAL),
                        App\Entities\Assignment::TYPE_EXTRAORDINARY => App\Entities\Assignment::typeName(App\Entities\Assignment::TYPE_EXTRAORDINARY),
                        App\Entities\Assignment::TYPE_OTHER => App\Entities\Assignment::typeName(App\Entities\Assignment::TYPE_OTHER),
                    ], request()->input('type'), ['class'=>'form-select', 'aria-describedby' => 'addon-type']) }}
                </div>
            </div>
            @if (!(isset($exclude) && in_array('detail', $exclude)))
            <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <span class="input-group-text" id="basic-addon1">{{ __('Detalle') }}</span>
                  {{ Form::text('detail', request()->input('detail'), ['class' => 'form-control']) }}
            </div>    
            @endif
    </div>
        </div>
    </div>
    <div class="col-12">
        <div class="row">
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
          @if ($report ?? '')
            <a href="{{ route('reports.movements', request()->input()) }}" class="btn btn-sm btn-outline-primary" title="{{__('Report')}}" target="_blank">
                  <span data-feather="bar-chart-2"></span> {{__('Report')}}
            </a>
          @endif
        </div>
    </div>
</form>
