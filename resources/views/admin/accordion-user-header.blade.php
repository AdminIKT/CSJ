<div class="small d-flex no-wrap align-items-center mt-2">
    @if ($entity->getUser()->getAvatar()) 
        <img src="{{ $entity->getUser()->getAvatar() }}" height="15px" width="15px" class="rounded-circle me-1"/> 
    @endif
    <p class="m-0">
    {{ ucwords(strtolower($entity->getUser()->getName())) }}
        <span data-bs-toggle="tooltip" class="small text-muted" title="{{ Carbon\Carbon::parse($entity->getCreated())->translatedFormat('m/d/Y h:i a') }}">
            {{ Carbon\Carbon::parse($entity->getCreated())->diffForHumans() }}
        </span>
    </p>
</div>
