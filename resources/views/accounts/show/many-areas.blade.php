<div class="table-responsive">
  <table class="table table-hover table-sm align-middle">
        <thead>
        <tr>
            <th>{{ __('acronimo') }}</th>
            <th>{{ __('Type') }}</th>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Real credit') }}</th>
            <th>{{ __('Compromised credit') }}</th>
            <th>{{ __('Available credit') }}</th>
            <th colspan="2">{{ __('Created') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $entity->getAcronym() }}</td>
            <td>{{ $entity->getTypeName() }}</td>
            <td>{{ $entity->getName() }}</td>
            <td>{{ number_format($entity->getCredit(), 2, ",", ".") }}€</td>
            <td>{{ number_format($entity->getCompromisedCredit(), 2, ",", ".") }}€</td>
            <td>{{ number_format($entity->getAvailableCredit(), 2, ",", ".") }}€</td>
            <td colspan="3">{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
        </tr>
        <tr class="bg-light">
            <td></td>
            <td colspan="8">
                <strong>{{ __('Areas') }}</strong>
            </td>
        </tr>
        @foreach ($entity->getSubaccounts() as $i => $subaccount)
            <tr>
                <td>{{ $subaccount->getArea()->getAcronym() }}</td>
                <td>{{ $subaccount->getArea() }}</td>
                <td colspan="2"></td>
                <td>{{ number_format($subaccount->getCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($subaccount->getCompromisedCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($subaccount->getAvailableCredit(), 2, ",", ".") }}€</td>
                <td colspan="">
                    <a href="{{ route('subaccounts.orders.create', ['subaccount' => $subaccount->getId()]) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('New order') }}">
                        <span data-feather="file"></span> {{ __('New order') }}
                    </a>
                </td>
                <td>
                    <div class="btn-group">
                        <button id="movement{$i}Btn" class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                </td>
            </tr>
        @endforeach
        </tbody>
  </table>
</div>
