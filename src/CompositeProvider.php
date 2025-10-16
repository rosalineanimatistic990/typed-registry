<?php

declare(strict_types=1);

namespace TypedRegistry;

/**
 * Tries multiple providers in order, returning the first non-null value.
 *
 * Useful for implementing fallback chains (e.g., environment → config file → defaults).
 */
final class CompositeProvider implements Provider
{
    /**
     * @param list<Provider> $providers
     */
    public function __construct(private array $providers)
    {
    }

    public function get(string $key): mixed
    {
        foreach ($this->providers as $provider) {
            $value = $provider->get($key);
            if ($value !== null) {
                return $value;
            }
        }

        return null;
    }
}
