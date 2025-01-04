<?php

namespace Namu\WireChat\Livewire\Widgets;

use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Reflector;
use Livewire\Component;
use Livewire\Mechanisms\ComponentRegistry;
use Namu\WireChat\Models\Conversation;

class WireChat extends Component
{
    public ?string $activeWireChatWidgetComponent = null;

    public array $widgetComponents = [];

    public function resetState(): void
    {
        $this->widgetComponents = [];
        $this->activeWireChatWidgetComponent = null;
    }

    public static function modalAttributes(): array
    {
        return [
            'closeOnEscape' => true,
            'closeOnEscapeIsForceful' => false,
            'dispatchCloseEvent' => true,
            'destroyOnClose' => true,
        ];
    }

    public function openChatWidget($conversationId, $arguments = [], $modalAttributes = []): void
    {
        $component = 'chat';
        // $componentClass = app(ComponentRegistry::class)->getClass($component);

        // Generate a unique ID using the conversationId and arguments
        $id = md5($component.$conversationId.serialize($arguments));

        // Merge modal attributes with defaults
        $defaultModalAttributes = [
            'closeOnEscape' => true,
            'closeOnEscapeIsForceful' => true,
            'dispatchCloseEvent' => true,
            'destroyOnClose' => true,
        ];
        $modalAttributes = array_merge($defaultModalAttributes, $modalAttributes);
        $this->widgetComponents = [
            $id => [
                'name' => $component,
                'conversationId' => $conversationId,
                'modalAttributes' => $modalAttributes,
            ],
        ];

        $this->activeWireChatWidgetComponent = $id;

        /*! Changed listener name to activeChatWidgetComponentChanged to not interfere with main modal */
        $this->dispatch('activeChatWidgetComponentChanged', id: $id);
    }

    public function resolveComponentProps(array $attributes, Component $component): Collection
    {

        return $this->getPublicPropertyTypes($component)
            ->intersectByKeys($attributes)
            ->map(function ($className, $propName) use ($attributes) {
                $resolved = $this->resolveParameter($attributes, $propName, $className);

                return $resolved;
            });
    }

    protected function resolveParameter($attributes, $parameterName, $parameterClassName)
    {
        $parameterValue = $attributes[$parameterName];

        if ($parameterValue instanceof UrlRoutable) {
            return $parameterValue;
        }

        if (enum_exists($parameterClassName)) {
            $enum = $parameterClassName::tryFrom($parameterValue);

            if ($enum !== null) {
                return $enum;
            }
        }

        $instance = app()->make($parameterClassName);

        if (! $model = $instance->resolveRouteBinding($parameterValue)) {
            throw (new ModelNotFoundException)->setModel(Conversation::class, [$parameterValue]);
        }

        return $model;
    }

    public function getPublicPropertyTypes($component): Collection
    {
        $types = collect($component->all())
            ->map(function ($value, $name) use ($component) {
                return Reflector::getParameterClassName(new \ReflectionProperty($component, $name));
            })
            ->filter();

        return $types;

    }

    public function destroyChatWidget($id): void
    {
        unset($this->widgetComponents[$id]);
    }

    public function getListeners(): array
    {
        return [
            'openChatWidget',
            'destroyChatWidget',
            'closeChatWidget',
        ];
    }

    public function render()
    {
        return view('wirechat::livewire.widgets.wire-chat');
    }
}
