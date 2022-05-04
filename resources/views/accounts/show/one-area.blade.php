<div class="table-responsive">
  <table class="table table-hover table-sm align-middle">
        <thead>
        <tr>
            <th>{{ __('acronimo') }}</th>
            <th>{{ __('Type') }}</th>
            <th>{{ __('Area') }}</th>
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
            <td>{{ $entity->getTypeName() }}</td>
            <td>{{ implode(", ", $entity->getAreas()->map(function ($e) { return $e->getName(); })->toArray()) }}</td>
            <td>{{ implode(", ", $entity->getUsers()->map(function ($e) { return $e->getName(); })->toArray()) }}</td>
            <td>{{ number_format($entity->getCredit(), 2, ",", ".") }}€</td>
            <td>{{ number_format($entity->getCompromisedCredit(), 2, ",", ".") }}€</td>
            <td>{{ number_format($entity->getAvailableCredit(), 2, ",", ".") }}€</td>
            <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
        </tr>
        </tbody>
  </table>
</div>
