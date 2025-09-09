<?php

namespace Wirechat\Wirechat\Panel\Concerns;

use Closure;

trait HasChatActions
{
    protected bool|Closure $createChatAction = false;

    protected bool|Closure $clearChatAction = true;

    protected bool|Closure $deleteChatAction = true;

    public function createChatAction(bool|Closure $condition = true): static
    {
        $this->createChatAction = $condition;

        return $this;
    }

    public function clearChatAction(bool|Closure $condition = true): static
    {
        $this->clearChatAction = $condition;

        return $this;
    }

    public function deleteChatAction(bool|Closure $condition = true): static
    {
        $this->deleteChatAction = $condition;

        return $this;
    }

    public function hasCreateChatAction(): bool
    {
        return (bool) $this->evaluate($this->createChatAction);
    }

    public function hasClearChatAction(): bool
    {
        return (bool) $this->evaluate($this->clearChatAction);
    }

    public function hasDeleteChatAction(): bool
    {
        return (bool) $this->evaluate($this->deleteChatAction);
    }
}
