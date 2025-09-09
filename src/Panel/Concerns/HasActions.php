<?php

namespace Wirechat\Wirechat\Panel\Concerns;

use Closure;

trait HasActions
{
    protected bool|Closure $redirectToHomeAction = false;

    public function redirectToHomeAction(bool|Closure $condition = true): static
    {
        $this->redirectToHomeAction = $condition;

        return $this;
    }

    public function hasRedirectToHomeAction(): bool
    {
        return (bool) $this->evaluate($this->redirectToHomeAction);
    }
}
