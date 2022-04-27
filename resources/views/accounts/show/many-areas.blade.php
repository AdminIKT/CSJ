<div class="table-responsive">
  <table class="table table-hover table-sm align-middle">
        <thead>
        <tr>
            <th>{{ __('acronimo') }}</th>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Users') }}</th>
            <th>{{ __('Real credit') }}</th>
            <th>{{ __('Compromised credit') }}</th>
            <th>{{ __('Available credit') }}</th>
            <th>{{ __('Created') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $entity->getAcronym() }}</td>
            <td>{{ $entity->getName() }}</td>
            <td>{{ implode(", ", $entity->getUsers()->map(function ($e) { return $e->getName(); })->toArray()) }}</td>
            <td>{{ number_format($entity->getCredit(), 2, ",", ".") }}€</td>
            <td>{{ number_format($entity->getCompromisedCredit(), 2, ",", ".") }}€</td>
            <td>{{ number_format($entity->getAvailableCredit(), 2, ",", ".") }}€</td>
            <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
        </tr>
        <tr>
            <td colspan="7" class="bg-light">
                <strong>{{ __('Areas') }}</strong>
            </td>
        </tr>
        @foreach ($entity->getSubaccounts() as $subaccount)
            <tr>
                <td>{{ $subaccount->getAcronym() }}</td>
                <td>{{ $subaccount->getArea() }}</td>
                <td></td>
                <td>{{ number_format($subaccount->getCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($subaccount->getCompromisedCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($subaccount->getAvailableCredit(), 2, ",", ".") }}€</td>
                <td>
                <a href="{{ route('subaccounts.orders.create', ['subaccount' => $subaccount->getId()]) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('New order') }}">
                        <span data-feather="file"></span>
                    </a>
                    <a href="{{ route('subaccounts.assignments.create', ['subaccount' => $subaccount->getId()]) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('New assignment') }}">
                        <span data-feather="dollar-sign"></span>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
  </table>
</div>
