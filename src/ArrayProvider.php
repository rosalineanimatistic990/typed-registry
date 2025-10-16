<?php

declare(strict_types=1);

namespace TypedRegistry;

/**
 * Array-backed provider.
 *
 * Useful for tests, preloaded configuration, or simple use cases.
 */
final class ArrayProvider implements Provider
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(private array $data)
    {
    }

    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }
}
