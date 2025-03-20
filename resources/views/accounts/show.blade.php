@extends('sj_layout')
@section('title')
    {{ __('Account :name', ['name' => $entity->getSerial()]) }} <small>({{$entity->getName()}})</small>
@endsection
@section('btn-toolbar')
    @include('accounts.shared.header', ['entity' => $entity])
@endsection

@section('content')

<div class="row">
    <div class="table-responsive col-sm-12 col-md-6">
        <table class="table table-sm align-middle table-bordered border-white">
            <tr>
                <th colspan="{{ $entity->getSubaccounts()->count() === 1 ? '1': '2' }}">{{ __('acronimo') }}</th>
                <th>{{ __('Type') }}</th>
                @if ($entity->getSubaccounts()->count() === 1)
                <th>{{ __('Area') }}</th>
                @endif
            </tr>
            <tr class="table-secondary">
                <td colspan="{{ $entity->getSubaccounts()->count() === 1 ? '1': '2' }}">
                    <span class="cbg me-1 {{ $entity->getStatusColor() }}"> </span>
                    {{ $entity->getSerial() }}
                    <span class="small text-muted">{{ $entity->getName() }}</span>
                </td>
                <td>{{ $entity->getTypeName() }}</td>
                @if ($entity->getSubaccounts()->count() === 1)
                <td>
                    <!--<a href="{{ route('areas.show', ['area' => $entity->getSubaccounts()->first()->getArea()->getId()]) }}">-->
                        {{ $entity->getSubaccounts()->first()->getArea()->getAcronym() }}
                    <!--</a>-->
                    <span class="small text-muted">{{ $entity->getSubaccounts()->first()->getArea() }}</span>
                </td>
                @endif
            </tr>
            <tr>
                <th>{{ __('Real credit') }}</th>
                <th>{{ __('Compromised credit') }}</th>
                <th>{{ __('Available credit') }}</th>
            </tr>
            <tr class="table-secondary">
                <td>{{ number_format($entity->getCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($entity->getCompromisedCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($entity->getAvailableCredit(), 2, ",", ".") }}€</td>
            </tr>
            @if ($entity->getSubaccounts()->count() > 1)
            <tr>
                <th colspan="2">{{ __('Users') }}</th>
                <th>{{ __('Created') }}</th>
            </tr>
            <tr class="table-secondary">
                <td colspan="2">
                    {{ implode(", ", $entity->getUsers()->map(function ($e) { return $e->getName(); })->toArray()) }}
                </td>
                <td>
                    {{ Carbon\Carbon::parse($entity->getCreated())->diffForHumans() }}
                </td>
            </tr>
            <tr class="">
                <td colspan="3"><b>{{ __('Detail') }}:</b> {{ $entity->getDetail() }}</td>
            </tr>
            @endif
        </table>
    </div>
    @if ($entity->getSubaccounts()->count() > 1)
    <div class="table-responsive col-sm-12 col-md-6">
        <table class="table table-sm align-middle table-bordered border-white">
        <tr>
            <th>{{ __('Areas') }}</th>
            <th>{{ __('Real credit') }}</th>
            <th>{{ __('Compromised credit') }}</th>
            <th>{{ __('Available credit') }}</th>
        </tr>
        @foreach ($entity->getSubaccounts() as $i => $subaccount)
            <tr class="table-secondary">
                <td>
                    <!--<a href="{{ route('areas.show', ['area' => $subaccount->getArea()->getId()]) }}">-->
                        {{ $subaccount->getArea()->getAcronym() }}
                    <!--</a>-->
                    <span class="small text-muted">{{ $subaccount->getArea() }}</span>
                </td>
                <td>{{ number_format($subaccount->getCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($subaccount->getCompromisedCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($subaccount->getAvailableCredit(), 2, ",", ".") }}€</td>
            </tr>
            <tr>
                <td class="text-center py-2" colspan="4">
                    <div class="btn-group btn-group-sm">
                    @can('viewany', \App\Entities\Account::class)
                    <button type="button" class="btn btn-outline-secondary" title="{{ __('Copy to clipboard') }}"
                            data-clip="C#{{ $subaccount->getSerial() }}" onclick="copyToClipboard($(this))">
                        <span class="clip">{{ __("Copied") }}</span>
                        <span class="bx bxs-copy"></span>
                    </button>
                    @endcan
                        @can('view', $entity)
                        <a href="{{ route('subaccounts.orders.create', ['subaccount' => $subaccount->getId()]) }}" class="btn btn-outline-secondary" title="{{ __('New order') }}">
                            <span class="bx bx-xs bxs-file"></span> {{ __('New order') }}
                        </a>
                        @endcan
                        @can('update', $entity) <!-- FIXME: $subaccount gives error -->
                        <div class="btn-group btn-group-sm">
                            <button id="movement{$i}Btn" class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="bx bx-xs bx-dollar"></span> {{ __('New movement') }} 
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="movementi{$i}Btn">
                                <li>
                                    <a href="{{ route('subaccounts.assignments.create', ['subaccount' => $subaccount->getId()]) }}" class="dropdown-item">+<span class="bx bx-xs bx-dollar"></span> {{ __('New assignment') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('subaccounts.charges.create', ['subaccount' => $subaccount->getId()]) }}" class="dropdown-item">-<span class="bx bx-xs bx-dollar"></span> {{ __('New charge') }}</a>
                                </li>
                            </ul>
                        </div>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
        </table>
    </div>
    @endif
    @if ($entity->getSubaccounts()->count() === 1)
    <div class="table-responsive col">
        <table class="table table-sm align-middle table-bordered border-white">
            <tr>
                <th>{{ __('Users') }}</th>
                <th>{{ __('Created') }}</th>
            </tr>
            <tr class="table-secondary">
                <td>
                    {{ implode(", ", $entity->getUsers()->map(function ($e) { return $e->getName(); })->toArray()) }}
                </td>
                <td>
                    {{ Carbon\Carbon::parse($entity->getCreated())->diffForHumans() }}
                </td>
            </tr>
            <tr class="">
                <td colspan="2"><b>{{ __('Detail') }}:</b> {{ $entity->getDetail() }}</td>
            </tr>
        </table>
    </div>
    @endif
</div>

<ul class="nav nav-tabs justify-content-center border-0">
  <li class="nav-item">
    <a class='nav-link {{request()->is("accounts/{$entity->getId()}")?" active":"" }}' href="{{ route('accounts.show', ['account' => $entity->getId()]) }}">
        <span class="bx bx-xs bxs-file"></span> {{ __('Orders') }}
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('accounts/*/movements')?' active':'' }}" href="{{ route('accounts.movements.index', ['account' => $entity->getId()]) }}">
        <span class="bx bx-xs bx-dollar"></span> {{ __('Movements') }}
    </a>
  </li>
</ul>

<div class="bg-white border rounded rounded-5 px-2 mb-2">
    @yield('body', View::make('accounts.body', [
        'collection' => $collection, 
        'entity' => $entity, 
        'suppliers' => $suppliers ?? [],
        'perPage' => $perPage
    ]))
</div>
@endsection
