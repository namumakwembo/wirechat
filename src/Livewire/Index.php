<?php

namespace Namu\WireChat\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Chats')]
    public function render()
    {
        return view('wirechat::livewire.index')
            ->layout(config('wirechat.layout', 'wirechat::layouts.app'));

    }
}
