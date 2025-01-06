<?php

namespace Namu\WireChat\Traits;

use Livewire\Attributes\Locked;

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
    public function isWidget()
    {
        return $this->widget;
    }
}
