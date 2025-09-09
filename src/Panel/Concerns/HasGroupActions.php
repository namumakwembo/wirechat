<?php

namespace Wirechat\Wirechat\Panel\Concerns;

use Closure;

trait HasGroupActions
{
    protected bool|Closure $createGroupAction = false;

    public function createGroupAction(bool|Closure $condition = true): static
    {
        $this->createGroupAction = $condition;

        return $this;
    }

    public function hasCreateGroupAction(): bool
    {
        return (bool) $this->evaluate($this->createGroupAction);
    }
}
