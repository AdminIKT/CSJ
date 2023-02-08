<button data-bs-toggle="collapse" data-bs-target="#collapseForm" role="button" aria-expanded="false" aria-controls="collapseForm" class="text-decoration-none btn btn-sm btn-outline-secondary btn-filter m-1 ms-0 collapsed">
    <i data-feather="search"></i> {{ __('Filter') }}
</button>
<form id="collapseForm" action="{{ route('suppliers.index') }}" method="GET" class="collapse mb-3">
    <div class="row row-cols-lg-5">
        <div class="col-md-4">
            <div class="input-group input-group-sm mb-3">
            <span class="input-group-text" id="addon-status">{{ __('Status') }}</span>
            {{ Form::select('status', [
            null => __('selecciona'),
            \App\Entities\Supplier::STATUS_PENDING       => \App\Entities\Supplier::statusName(\App\Entities\Supplier::STATUS_PENDING),
            \App\Entities\Supplier::STATUS_VALIDATED     => \App\Entities\Supplier::statusName(\App\Entities\Supplier::STATUS_VALIDATED),
            \App\Entities\Supplier::STATUS_RECOMMENDABLE => \App\Entities\Supplier::statusName(\App\Entities\Supplier::STATUS_RECOMMENDABLE),
            \App\Entities\Supplier::STATUS_NO_ACCEPTABLE => \App\Entities\Supplier::statusName(\App\Entities\Supplier::STATUS_NO_ACCEPTABLE),
            \App\Entities\Supplier::STATUS_INACTIVE      => \App\Entities\Supplier::statusName(\App\Entities\Supplier::STATUS_INACTIVE),
            ], request()->input('status'), ['class'=>'form-select', 'aria-describedby' => 'addon-status']) }}
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="input-group input-group-sm mb-3">
              <span class="input-group-text" id="basic-addon1">{{ __('Nif') }}</span>
              {{ Form::text('nif', request()->input('nif'), ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="input-group input-group-sm mb-3">
              <span class="input-group-text" id="basic-addon1">{{ __('Name') }}</span>
              {{ Form::text('name', request()->input('name'), ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="input-group input-group-sm mb-3">
              <span class="input-group-text" id="addon-type">{{ __('Region') }}</span>
              </select>
              {{ Form::select('region', [null => __('selecciona')] + $regions, request()->input('region'), ['class'=>'form-select', 'aria-describedby' => 'addon-type']) }}
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="input-group input-group-sm mb-3">
              <span class="input-group-text" id="addon-type">{{ __('City') }}</span>
              </select>
              {{ Form::select('city', [null => __('selecciona')] + $cities, request()->input('city'), ['class'=>'form-select', 'aria-describedby' => 'addon-type']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="input-group input-group-sm mb-3">
              <span class="input-group-text" id="basic-addon1">{{ __('Predicted') }}</span>
              <div class="input-group-prepend">
                  {{ Form::select('estimatedOp', [
                    '>=' => '>=',
                    '==' => '==',
                    '<=' => '<=',
                  ], request()->input('estimatedOp'), ['class'=>'form-select form-select-sm', 'aria-describedby' => 'addon-type']) }}
              </div>
              {{ Form::number('estimated', request()->input('estimated'), ['class' => 'form-control', 'step' => '0.01', 'min' => 0]) }}
              <span class="input-group-text">€</span>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="input-group input-group-sm mb-3">
              <span class="input-group-text" id="basic-addon1">{{ __('Invoiced') }}</span>
              <div class="input-group-prepend">
                  {{ Form::select('creditOp', [
                    '>=' => '>=',
                    '==' => '==',
                    '<=' => '<=',
                  ], request()->input('creditOp'), ['class'=>'form-select form-select-sm', 'aria-describedby' => 'addon-type']) }}
              </div>
              {{ Form::number('credit', request()->input('credit'), ['class' => 'form-control', 'step' => '0.01', 'min' => 0]) }}
              <span class="input-group-text">€</span>
            </div>
        </div>
    </div>
    <div class="row">
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
                    <i class="bx bx-search"></i>
              </button>
              <a href="{{ route('reports.suppliers', request()->input()) }}" class="btn btn-sm btn-outline-primary" title="{{__('Report')}}" target="_blank">
                    <i class="bx bx-bar-chart"></i>
              </a>
            </div>
        </div>
    </div>
</form>
