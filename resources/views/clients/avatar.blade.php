@if ($client->avatar)
<img class="rounded-full w-16 h-16" alt="{{ $client->name . 'avatar' }}"
    src="{{ $client->getAvatarUrl() }}">
@else
<img class="w-16" alt="{{ $client->name . 'avatar' }}"
    src="/img/avatardefault.svg">
@endif
