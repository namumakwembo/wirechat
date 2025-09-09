<?php

namespace Wirechat\Wirechat\Panel\Concerns;

use Closure;

trait HasDeleteMessageActions
{
    protected bool|Closure $deleteMessageActions = true;

    public function deleteMessageActions(bool|Closure $condition = true): static
    {
        $this->deleteMessageActions = $condition;

        return $this;
    }

    public function hasDeleteMessageActions(): bool
    {
        return (bool) $this->evaluate($this->deleteMessageActions);
    }

    //    protected bool|Closure $deleteMessageForMeAction = false;
    //
    //    protected bool|Closure $deleteMessageForEveryoneAction = true;
    //
    //
    //    public function deleteMessageForMeAction(bool|Closure $condition = true): static
    //    {
    //        $this->deleteMessageForMeAction = $condition;
    //
    //        return $this;
    //    }
    //
    //    public function deleteMessageForEveryoneAction(bool|Closure $condition = true): static
    //    {
    //        $this->deleteMessageForEveryoneAction = $condition;
    //
    //        return $this;
    //    }
    //
    //    public function hasDeleteMessageForMeAction(): bool
    //    {
    //        return (bool) $this->evaluate($this->deleteMessageForMeAction);
    //    }
    //
    //    public function hasDeleteMessageForEveryoneAction(): bool
    //    {
    //        return (bool) $this->evaluate($this->deleteMessageForEveryoneAction);
    //    }

}
