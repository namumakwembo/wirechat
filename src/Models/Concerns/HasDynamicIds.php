<?php

namespace Wirechat\Wirechat\Models\Concerns;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Wirechat\Wirechat\Facades\Wirechat;

trait HasDynamicIds
{
    /**
     * Initialize the trait.
     */
    public function initializeHasDynamicIds()
    {
        $this->usesUniqueIds = Wirechat::usesUuidForConversations();
    }

    /**
     * Generate a new unique key for the model (only for UUIDs).
     */
    public function newUniqueId(): ?string
    {
        if (Wirechat::usesUuidForConversations()) {
            // Prefer UUIDv7 if supported
            // @phpstan-innore-next-line
            if (method_exists(Str::class, 'uuid7')) {
                return (string) Str::uuid7();
            }

            return (string) Str::uuid();
        }

        return null;
    }

    /**
     * Determine if the given key is valid.
     */
    protected function isValidUniqueId($value): bool
    {
        if (Wirechat::usesUuidForConversations()) {
            return Str::isUuid($value);
        }

        // For integer IDs, check if the value is a positive integer
        return is_numeric($value) && (int) $value == $value && $value > 0;
    }

    /**
     * Get the columns that should receive a unique identifier.
     */
    public function uniqueIds(): array
    {
        return $this->usesUniqueIds ? [$this->getKeyName()] : [];
    }

    /**
     * Get the name of the route key column.
     *
     * - Old installs (UUID as PK): "id"
     * - New installs (int PK + uuid): "uuid"
     */
    public function getRouteKeyName(): string
    {
        return Wirechat::usesUuidForConversations()
            ? $this->getKeyName() // "id" (may be UUID in old installs)
            : 'uuid';             // new installs prefer uuid for cleaner URLs
    }

    /**
     * Get the actual route key value for the model.
     */
    public function getRouteKey(): mixed
    {
        return $this->getAttribute($this->getRouteKeyName());
    }

    /**
     * Retrieve the model for a bound value.
     *
     * Falls back between "uuid" and "id" for backwards compatibility.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        // If a field is explicitly requested, validate it
        if ($field && in_array($field, $this->uniqueIds()) && ! $this->isValidUniqueId($value)) {
            $this->handleInvalidUniqueId($value, $field);
        }

        // Determine which field to bind against
        $routeKey = $field ?? $this->getRouteKeyName();

        // Try primary binding first
        $result = $query->where($routeKey, $value);

        // For new installs, allow fallback: /conversations/{id}
        if (! Wirechat::usesUuidForConversations() && $routeKey === 'uuid') {
            $result->orWhere($this->getKeyName(), $value);
        }

        return $result;
    }

    /**
     * Get the auto-incrementing key type.
     */
    public function getKeyType(): string
    {
        return Wirechat::usesUuidForConversations() ? 'string' : 'int';
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     */
    public function getIncrementing(): bool
    {
        return ! Wirechat::usesUuidForConversations();
    }

    /**
     * Throw an exception for the given invalid unique ID.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function handleInvalidUniqueId($value, $field)
    {
        throw (new ModelNotFoundException)->setModel(get_class($this), $value);
    }

    /**
     * Boot the trait.
     */
    protected static function bootHasDynamicIds()
    {
        if (Wirechat::usesUuidForConversations()) {
            static::creating(function ($model) {
                if (! $model->getKey()) {
                    $model->{$model->getKeyName()} = $model->newUniqueId();
                }
            });
        }
    }
}
