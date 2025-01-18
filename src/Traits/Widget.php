<?php

namespace Namu\WireChat\Traits;

use Livewire\Attributes\Locked;
use Namu\WireChat\Facades\WireChat;
use Namu\WireChat\Livewire\Chat\Chats;

/**
 * Trait Actionable
 */
trait Widget
{
    #[Locked]
    public bool $widget = false;

    /**
     * ----------------------------------------
     * ----------------------------------------
     * Check if is Widget
     * --------------------------------------------
     */
    public function isWidget(): bool
    {
        return $this->widget;
    }


    /**
     * Handle the termination of the component.
     * 
     * If the component is a widget, it dispatches events to refresh the chat list
     * and notify the listener to close the chat. Otherwise, it redirects to the chats page.
     */
    public function handleComponentTermination()
    {
        if ($this->isWidget()) {
            // Dispatch an event to refresh the chats list in the widget
            $this->dispatch('hardRefresh')->to(Chats::class);

            // Notify the listener to close the current chat widget
            $this->dispatch('close-chat');
        } else {
            // Redirect to the main chats page
            $this->redirectRoute(WireChat::indexRouteName());
        }
    }
}
