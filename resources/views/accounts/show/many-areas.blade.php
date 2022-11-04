<div class="table-responsive">
    <table class="table table-sm align-middle table-bordered border-white">
        <thead>
            <tr>
                <th>{{ __('acronimo') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Real credit') }}</th>
                <th>{{ __('Compromised credit') }}</th>
                <th>{{ __('Available credit') }}</th>
                <th>{{ __('Created') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr class="table-secondary">
                <td title="{{ $entity->getName() }}">{{ $entity->getSerial() }}</td>
                <td>{{ $entity->getTypeName() }}</td>
                <td>{{ number_format($entity->getCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($entity->getCompromisedCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($entity->getAvailableCredit(), 2, ",", ".") }}€</td>
                <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
            </tr>
        </tbody>
        <thead>
            <tr>
                <th colspan="6">
                    <strong>{{ __('Areas') }}</strong>
                </th>
            </tr>
        </thead>
        <tbody>
        @foreach ($entity->getSubaccounts() as $i => $subaccount)
            <tr class="table-secondary">
                <td>{{ $subaccount->getArea()->getAcronym() }}</td>
                <td><a href="{{ route('areas.show', ['area' => $subaccount->getArea()->getId()]) }}">{{ $subaccount->getArea() }}</a></td>
                <td>{{ number_format($subaccount->getCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($subaccount->getCompromisedCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($subaccount->getAvailableCredit(), 2, ",", ".") }}€</td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        @can('view', $entity)
                        <a href="{{ route('subaccounts.orders.create', ['subaccount' => $subaccount->getId()]) }}" class="btn btn-light" title="{{ __('New order') }}">
                            <span data-feather="file"></span> {{ __('New order') }}
                        </a>
                        @endcan
                        @can('update', $entity) <!-- FIXME: $subaccount gives error -->
                        <div class="btn-group btn-group-sm">
                            <button id="movement{$i}Btn" class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span data-feather="shopping-cart"></span> {{ __('New movement') }} 
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="movementi{$i}Btn">
                                <li>
                                    <a href="{{ route('subaccounts.assignments.create', ['subaccount' => $subaccount->getId()]) }}" class="dropdown-item">+<span data-feather="dollar-sign"></span> {{ __('New assignment') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('subaccounts.charges.create', ['subaccount' => $subaccount->getId()]) }}" class="dropdown-item">-<span data-feather="dollar-sign"></span> {{ __('New charge') }}</a>
                                </li>
                            </ul>
                        </div>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
        <thead>
            <tr>
                <th colspan="6">
                    <strong>{{ __('Users') }}</strong>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="">
                <td colspan="6">
                    {{ implode(", ", $entity->getUsers()->map(function ($e) { return $e->getName(); })->toArray()) }}
                </td>
            </tr>
        </tbody>
    </table>
</div>
