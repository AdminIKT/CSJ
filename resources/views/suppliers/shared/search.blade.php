<button data-bs-toggle="collapse" data-bs-target="#collapseForm" role="button" aria-expanded="false" aria-controls="collapseForm" class="text-decoration-none btn btn-sm btn-outline-secondary btn-filter m-1 ms-0 collapsed">
    <i data-feather="search"></i> {{ __('Filter') }}
</button>
<form id="collapseForm" action="{{ route('suppliers.index') }}" method="GET" class="row collapse mb-3">
<div class="col-12"><div class="row">
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="basic-addon1">{{ __('Nif') }}</span>
          {{ Form::text('nif', request()->input('nif'), ['class' => 'form-control']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="basic-addon1">{{ __('Name') }}</span>
          {{ Form::text('name', request()->input('name'), ['class' => 'form-control']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-type">{{ __('Region') }}</span>
          </select>
          {{ Form::select('region', [null => __('selecciona')] + $regions, request()->input('region'), ['class'=>'form-select', 'aria-describedby' => 'addon-type']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-type">{{ __('City') }}</span>
          </select>
          {{ Form::select('city', [null => __('selecciona')] + $cities, request()->input('city'), ['class'=>'form-select', 'aria-describedby' => 'addon-type']) }}
        </div>
    </div>
</div></div>
<div class="col-12"><div class="row">
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-type">{{ __('Recommendable') }}</span>
          {{ Form::select('recommendable', [
              null  => __('selecciona'),
              true  => __('Yes'),
              false => __('No'),
          ], request()->input('recommendable'), ['class'=>'form-select']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-type">{{ __('Acceptable') }}</span>
          {{ Form::select('acceptable', [
              null  => __('selecciona'),
              true  => __('Yes'),
              false => __('No'),
          ], request()->input('acceptable'), ['class'=>'form-select']) }}
        </div>
    </div>
    <div class="col-12">
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
          <a href="{{ route('reports.suppliers', request()->input()) }}" class="btn btn-sm btn-outline-primary" title="{{__('Report')}}" target="_blank">
                <span data-feather="bar-chart-2"></span> {{__('Report')}}
          </a>
        </div>
    </div>
</div></div>
</form>
