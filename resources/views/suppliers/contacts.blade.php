<div class="table-responsive">
    <table class="table table-hover table-sm">
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
                    <a href="{{ route('suppliers.contacts.edit', ['supplier' => $entity->getId(), 'contact' => $contact->getId()]) }}" class='btn btn-sm btn-outline-secondary {{request()->is("suppliers/{$entity->getId()}/contacts/{$contact->getId()}/edit") ? "active" : ""}}'>
                        <span data-feather="edit-2"></span>
                   </a>
                {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit']) }}
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
