<button data-bs-toggle="collapse" data-bs-target="#collapseForm" role="button" aria-expanded="false" aria-controls="collapseForm" class="text-decoration-none btn btn-sm btn-outline-secondary btn-filter m-1 ms-0 collapsed">
    <i class="bx bx-xs bx-search-alt-2"></i> {{ __('Filter') }}
</button>
<form id="collapseForm" action="{{ $route }}" method="GET" class="row collapse mb-3">
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-email">{{ __('Email') }}</span>
          {{ Form::text('email', request()->input('email'), ['class' => 'form-control', 'aria-describedby' => 'addon-email']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-name">{{ __('Name') }}</span>
          {{ Form::text('name', request()->input('name'), ['class' => 'form-control', 'aria-describedby' => 'addon-name']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-role">{{ __('Role') }}</span>
          {{ Form::select('role', [null => __('selecciona')] + ($roles ?? []), request()->input('role'), ['class'=>'form-select', 'aria-describedby' => 'addon-role']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-status">{{ __('Status') }}</span>
          {{ Form::select('status', [null => __('selecciona'), \App\Entities\User::STATUS_ACTIVE => \App\Entities\User::statusName(\App\Entities\User::STATUS_ACTIVE), \App\Entities\User::STATUS_INACTIVE => \App\Entities\User::statusName(\App\Entities\User::STATUS_INACTIVE)], request()->input('status', $statusDefault ?? null), ['class'=>'form-select', 'aria-describedby' => 'addon-status']) }}
        </div>
    </div>

    <div class="col-12">
        <div class="input-group input-group-sm mb-3">
            <span class="input-group-text" id="addon-perpage">{{ __('Pagination') }}</span>
            {{ Form::select('perPage', [null =>  __('All'), 5 => 5, 10 => 10, 20 => 20, 50 => 50], request()->input('perPage', $perPage ?? null), ['class'=>'form-select form-select-sm']) }}
            <button class="btn btn-outline-primary" type="submit" id="button-addon2">
              <i class="bx bx-search"></i>
            </button>
        </div>
    </div>
</form>
