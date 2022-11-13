<div class="table-responsive">
    <table class="table table-sm align-middle">
    <thead>
        <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Position') }}</th>
            <th>{{ __('Email') }}</th>
            <th>{{ __('Phone') }}</th>
            <th>{{ __('Actions') }}</th>
        </tr>
    </thead>
    <tbody> 
        @foreach ($entity->getContacts() as $contact)
        <tr>
            <td>{{ $contact->getName() }}</td>
            <td>{{ $contact->getPosition() }}</td>
            <td>{{ $contact->getEmail() }}</td>
            <td>{{ $contact->getPhone() }}</td>
            <td>
                {{ Form::open([
                    'route' => ['suppliers.contacts.destroy', $entity->getId(), $contact->getId()], 
                    'method' => 'delete',
                ]) }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('suppliers.contacts.edit', ['supplier' => $entity->getId(), 'contact' => $contact->getId()]) }}" class='btn btn-sm btn-light {{request()->is("suppliers/{$entity->getId()}/contacts/{$contact->getId()}/edit") ? "active" : ""}}'>
                        <i class="bx bx-pencil"></i>
                   </a>
                {{ Form::button('<i class="bx bx-trash"></i>', ['class' => 'btn btn-light', 'type' => 'submit', 'disabled' => $entity->getContacts()->count() > 1 ? false : true]) }}
                <!--
                <a class="btn btn-light" onclick="return confirm('Are you sure?')" href="{{route('suppliers.contacts.destroy', ['supplier' => $entity->getId(), 'contact' => $contact->getId()])}}"><i class="bx bx-trash"></i></a>
                -->
                </div>
                {{ Form::close() }}
            </td>
        </tr>
        @endforeach
        <!--
        <tr>
            <td colspan="5" align="center">
                <a href="{{ route('suppliers.contacts.create', ['supplier' => $entity->getId()]) }}" class='btn btn-sm btn-outline-secondary {{request()->is("suppliers/{$entity->getId()}/contacts/create") ? "active" : "" }}'>
                    <span data-feather="user"></span> {{ __('New contact') }}
                </a>
            </td>
        </tr>
        -->
    </tbody> 
    </table>
</div>
