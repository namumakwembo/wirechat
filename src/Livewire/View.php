<?php

namespace Namu\WireChat\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;
use Namu\WireChat\Models\Conversation;

class View extends Component
{
    public $conversation_id;

    public $conversation;

    public function mount()
    {
        ///make sure user is authenticated
        abort_unless(auth()->check(), 401);

        //We remove deleted conversation incase the user decides to visit the delted conversation
        $this->conversation = Conversation::where('id', $this->conversation_id)->firstOrFail();

        // Check if the user belongs to the conversation
        abort_unless(auth()->user()->belongsToConversation($this->conversation), 403);

    }

    #[Title('Chats')]
    public function render()
    {
        return view('wirechat::livewire.view')
            ->layout(config('wirechat.layout', 'wirechat::layouts.app'));
    }
}
