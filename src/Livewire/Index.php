<?php

namespace Namu\WireChat\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Layout('wirechat::layouts.app')]
    #[Title('Chats')]
    public function render()
    {
        return view('wirechat::livewire.index');
    }
}
