@use('Namu\WireChat\Facades\WireChat')

<div 
x-data="{selectedConversationId:'{{request()->conversation_id??$selectedConversationId}}'}"
x-on:open-chat.window="selectedConversationId= $event.detail.conversation; $wire.selectedConversationId= $event.detail.conversation;"
x-init=" setTimeout(() => {
     conversationElement = document.getElementById('conversation-'+selectedConversationId);

     // Scroll to the conversation element
     if (conversationElement) {
         conversationElement.scrollIntoView({ behavior: 'smooth' });
     }
 }, 200);"
    class="flex flex-col bg-white/95 dark:bg-gray-900 transition-all h-full overflow-hidden w-full sm:p-3 border-r dark:border-gray-700  ">

    @php
        $authUser = auth()->user();
        $authId = $authUser->id;
    @endphp

    {{-- include header --}}
    @include('wirechat::livewire.chats.includes.header')

    <main x-data 

        @scroll.self.debounce="
           {{-- Detect when scrolled to the bottom --}}
            // Calculate scroll values
            scrollTop = $el.scrollTop;
            scrollHeight = $el.scrollHeight;
            clientHeight = $el.clientHeight;

            // Check if the user is at the bottom of the scrollable element
            if ((scrollTop + clientHeight) >= (scrollHeight - 1) && $wire.canLoadMore) {
                // Trigger load more if we're at the bottom
                await $nextTick();
                $wire.loadMore();
            }
            "
        class=" overflow-y-auto py-2   grow  h-full relative " style="contain:content">

        {{-- include search if true --}}
        @includeWhen(config('wirechat.allow_chats_search', false) == true,'wirechat::livewire.chats.includes.search')

        @if (count($conversations) > 0)
        
            <ul wire:loading.delay.long.remove wire:target="search" class="p-2 grid w-full spacey-y-2">
                @foreach ($conversations as $conversation)
                 {{-- include list item --}}
                  @include('wirechat::livewire.chats.includes.list-item')  
                @endforeach

            </ul>

            {{-- include load more if true --}}
             @includeWhen($canLoadMore,'wirechat::livewire.chats.includes.load-more-button')
        @else
            <div class="w-full flex items-center h-full justify-center">
                <h6 class=" font-bold text-gray-700 dark:text-white">No conversations yet</h6>
            </div>
        @endif
    </main>



</div>
