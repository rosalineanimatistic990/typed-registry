<?php

declare(strict_types=1);

namespace TypedRegistry;

/**
 * Closure-backed provider for quick integrations or adapters.
 *
 * Allows wrapping any callable that accepts a string key and returns a mixed value.
 */
final class CallbackProvider implements Provider
{
    /**
     * @param callable(string): mixed $getter
     */
    public function __construct(private $getter)
    {
    }

    public function get(string $key): mixed
    {
        return ($this->getter)($key);
    }
}
