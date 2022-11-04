<div class="table-responsive">
    <table class="table table-sm align-middle table-bordered border-white">
        <thead>
            <tr>
                <th>{{ __('acronimo') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Area') }}</th>
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
                <td>{{ implode(", ", $entity->getAreas()->map(function ($e) { return $e->getName(); })->toArray()) }}</td>
                <td>{{ number_format($entity->getCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($entity->getCompromisedCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($entity->getAvailableCredit(), 2, ",", ".") }}€</td>
                <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
            </tr>
        </tbody>
        <thead>
            <tr>
                <th colspan="7">
                    <strong>{{ __('Users') }}</strong>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="">
                <td colspan="7">
                    {{ implode(", ", $entity->getUsers()->map(function ($e) { return $e->getName(); })->toArray()) }}
                </td>
            </tr>
        </tbody>
    </table>
</div>
