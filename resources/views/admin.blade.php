@extends('sj_layout')
@section('title'){{ __('Control Panel') }}@endsection
@section('content')
<div id="admin-page" class="admin row" data-masonry='{"percentPosition": true }'>
    <div class="col-md-7 accordion accordion-flush" id="accountsAccordion">
        <h6><i class="bx bxs-credit-card"></i> {{ __('Accounts') }}</h6>
        <div class="input-group input-group-sm mb-2">
            <span class="input-group-text" id="addon-status"><i class="bx bx-xs bx-search-alt-2 me-1"></i>{{ __('Search') }}</span>
            <input type="text" class="form-control">
        </div>
        @foreach ($accounts as $account)

        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-{{ $account->getId() }}">
                <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-acc-{{ $account->getId() }}" aria-expanded="false" aria-controls="collapse-acc-{{ $account->getId() }}">

                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <div class="accordion-info">
                            <span class="cbg me-1 {{ $account->getStatusColor() }}" data-bs-toggle="tooltip" title="{{ $account->getStatusName() }}"> </span>
                            <span class="account-name">{{ $account->getName() }}</span>
                            <div class="my-2">
                                <span data-bs-toggle="tooltip" class="badge bg-light text-dark" title="{{ $account->getTypeName() }}">{{ $account->getType() }}</span>
                                <small class="bg-light text-dark rounded p-1">{{ __('Available credit') }}: {{ number_format($account->getAvailableCredit(), 2, ",", ".") }}€</small>
                            </div>
                        </div>
                        <h6 class="card-subtitle text-nowrap mx-4">{{ $account->getSerial() }}</h6>
                    </div>

                </button>
            </h2>
            <div id="collapse-acc-{{ $account->getId() }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $account->getId() }}" data-bs-parent="#accountsAccordion">
                <div class="accordion-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="me-md-4">
                            <table class="table align-middle bg-secondary text-light rounded">
                                <tr>
                                    <td>{{ __('Real credit') }}:</td>
                                    <td>{{ number_format($account->getCredit(), 2, ",", ".") }}€</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Compromised credit') }}:</td>
                                    <td>{{ number_format($account->getCompromisedCredit(), 2, ",", ".") }}€</td>
                                </tr>
                                <!--
                                <tr>
                                    <td>{{ __('Available credit') }}:</td>
                                    <td>{{ number_format($account->getAvailableCredit(), 2, ",", ".") }}€</td>
                                </tr>
                                -->
                            </table>
                        </div>
                        <div>
                            <p>
                                  <strong>{{ __('Areas') }}:</strong> {{ implode(", ", $account->getAreas()->map(function ($e) { return $e->getAcronym() . ": " . $e->getName(); })->toArray()) }}
                            </p>
                            <p>
                                  <strong>{{ __('Users') }}:</strong> {{ implode(", ", $account->getUsers()->map(function ($e) { return ucwords(strtolower($e->getName())); })->toArray()) }}
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="btn-toolbar justify-content-center"> 
                        @include('accounts.shared.header', ['entity' => $account])
                    </div>

                </div>
            </div>
        </div>

        @endforeach
    </div>

    <div class="col-md-5">

        <div class="panel border shadow-sm rounded p-2 mb-4">
            <h6><i class="bx bx-dollar"></i> {{ __('Totals') }}</h6>
            <table class="table align-middle m-0">
                <tr>
                    <td>{{ __('Real credit') }}:</td>
                    <td>{{ number_format($totals['credit'], 2, ",", ".") }}€</td>
                </tr>
                <tr>
                    <td>{{ __('Compromised credit') }}:</td>
                    <td>{{ number_format($totals['compromised'], 2, ",", ".") }}€</td>
                </tr>
                <tr class="bg-light">
                    <td class="border-0">{{ __('Available credit') }}:</td>
                    <td class="border-0">{{ number_format($totals['credit'] - $totals['compromised'], 2, ",", ".") }}€</td>
                </tr>
            </table>
        </div>

        <div class="panel border shadow-sm rounded p-2 mb-4">
            <h6><i class="bx bx-pulse"></i> {{ __('Last activity') }}</h6>
            <div class="accordion accordion-flush" id="actionsAccordion">
            @foreach ($actions as $action)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-act-{{ $action->getId() }}" aria-expanded="false" aria-controls="collapse-act-{{ $action->getId() }}">
                        @include('admin.accordion-action-header', ['entity' => $action])

                    </button>
                    </h2>
                    <div id="collapse-act-{{ $action->getId() }}" class="accordion-collapse collapse" data-bs-parent="#actionsAccordion">
                        <div class="accordion-body">
                            @if ($action instanceof \App\Entities\Action\OrderAction)
                                @include('admin.accordion-order-content', ['entity' => $action->getOrder()])
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
            <p class="border-top text-center my-0 pt-2">
                <a href="{{ route('actions.index') }}">{{ __('View all') }}</a>
            </p>
        </div>

        <div class="panel border shadow-sm rounded p-2 mb-4">
            <h6><i class="bx bx-file"></i> {{ __('Last orders') }}</h6>
            <div class="accordion accordion-flush" id="ordersAccordion">
            @foreach ($orders as $order)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-order-{{ $order->getId() }}" aria-expanded="false" aria-controls="collapse-order-{{ $order->getId() }}">
                        @include('admin.accordion-order-header', ['entity' => $order])
                    </button>
                    </h2>
                    <div id="collapse-order-{{ $order->getId() }}" class="accordion-collapse collapse" data-bs-parent="#ordersAccordion">
                        <div class="accordion-body">
                            @include('admin.accordion-order-content', ['entity' => $order])
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <p class="border-top text-center my-0 pt-2">
                <a href="{{ route('orders.index') }}">{{ __('View all') }}</a>
            </p>
        </div>

        <div class="panel border shadowi-sm rounded p-2 mb-4">
            <h6>-<i class="bx bx-dollar"></i> {{ __('Last charges') }}</h6>
            <div class="accordion accordion-flush" id="chargesAccordion">
            @foreach ($charges as $entity)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-charge-{{ $entity->getId() }}" aria-expanded="false" aria-controls="collapse-charge-{{ $entity->getId() }}">
                            @include('admin.accordion-movement-header', ['entity' => $entity])
                        </button>
                    </h2>
                    <div id="collapse-charge-{{ $entity->getId() }}" class="accordion-collapse collapse" data-bs-parent="#chargesAccordion">
                        <div class="accordion-body">
                            @if ($entity instanceof \App\Entities\OrderCharge)
                                @include('admin.accordion-order-content', ['entity' => $entity->getOrder()])
                            @endif
                            <p class="text-center my-1 mb-0">
                                @if (Str::length($entity->getDetail()))
                                    {{ $entity->getDetail() }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div> 
                </div>
            @endforeach
            </div>
            <p class="border-top text-center my-0 pt-2">
                <a href="{{ route('movements.index', ['movement' => \App\Entities\Charge::class ]) }}">{{ __('View all') }}</a>
            </p>
        </div>

        <div class="panel border shadow-sm rounded p-2 mb-4">
            <h6>+<i class="bx bx-dollar"></i> {{ __('Last assignments') }}</h6>
            <div class="accordion accordion-flush" id="assignmentsAccordion">
            @foreach ($assignments as $entity)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-assignment-{{ $entity->getId() }}" aria-expanded="false" aria-controls="collapse-assignment-{{ $entity->getId() }}">
                            @include('admin.accordion-movement-header', ['entity' => $entity])
                        </button>
                    </h2>
                    <div id="collapse-assignment-{{ $entity->getId() }}" class="accordion-collapse collapse" data-bs-parent="#assignmentsAccordion">
                        <div class="accordion-body">
                            <p class="text-center my-0">
                                @if (Str::length($entity->getDetail()))
                                    {{ $entity->getDetail() }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div> 
                </div>
            @endforeach
            </div>
            <p class="border-top text-center my-0 pt-2">
                <a href="{{ route('movements.index', ['movement' => \App\Entities\Assignment::class ]) }}">{{ __('View all') }}</a>
            </p>
        </div>

        <div class="panel border shadow-sm rounded p-2 mb-4">
            <h6><i class="bx bx-bell"></i> {{ __('Last incidences') }}</h6>
            <div class="accordion accordion-flush" id="incidencesAccordion">
            @foreach ($incidences as $entity)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-incidence-{{ $entity->getId() }}" aria-expanded="false" aria-controls="collapse-incidence-{{ $entity->getId() }}">
                            
                            <div>
                                <span class="cbg me-1 {{ $entity->getStatusColor() }}" data-bs-toggle="tooltip" title="{{ $entity->getStatusName() }}"> </span>{{ $entity->getSupplier()->getName() }}
                            @include('admin.accordion-user-header', ['entity' => $entity])
                            </div>
                        </button>
                    </h2>
                    <div id="collapse-incidence-{{ $entity->getId() }}" class="accordion-collapse collapse" data-bs-parent="#incidencesAccordion">
                        <div class="accordion-body">
                            @if ($entity->getOrder())
                                @include('admin.accordion-order-content', ['entity' => $entity->getOrder()])
                            @endif
                            <p class="text-center my-0">
                                @if (Str::length($entity->getDetail()))
                                    {{ $entity->getDetail() }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div> 
                </div>
            @endforeach
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/custom/accounts-accordion-filter.js') }}"></script>
@endsection
