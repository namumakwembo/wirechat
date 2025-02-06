<div class="flex gap-x-2 items-center">

    {{-- Only show if AUTH is onwer of message --}}
    @if ($belongsToAuth)
        <span class="font-bold text-xs dark:text-white/90 dark:font-normal">
            You:
        </span>
    @elseif(!$belongsToAuth && $group !== null)
        <span class="font-bold text-xs dark:text-white/80 dark:font-normal">
            {{ $lastMessage->sendable?->display_name }}:
        </span>
    @endif

    <p @class([
        'truncate text-sm dark:text-white  gap-2 items-center',
        'font-semibold text-black' =>
            !$isReadByAuth && !$lastMessage?->ownedBy($authUser),
        'font-normal text-gray-600' =>
            $isReadByAuth && !$lastMessage?->ownedBy($authUser),
        'font-normal text-gray-600' =>
            $isReadByAuth && $lastMessage?->ownedBy($authUser),
    ])>
        {{ $lastMessage->body != '' ? $lastMessage->body : ($lastMessage->hasAttachment() ? '📎 Attachment' : '') }}
    </p>

    <span class="font-medium px-1 text-xs shrink-0 text-gray-800 dark:text-gray-50">
        @if ($lastMessage->created_at->diffInMinutes(now()) < 1)
            now
        @else
            {{ $lastMessage->created_at->shortAbsoluteDiffForHumans() }}
        @endif
    </span>


</div>
