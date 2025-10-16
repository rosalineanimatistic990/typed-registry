<?php

declare(strict_types=1);

namespace TypedRegistry;

/**
 * Returns mixed values for string keys.
 *
 * Key format is provider-defined (dotted paths, env variable names, array keys, etc.).
 */
interface Provider
{
    /**
     * Retrieve a value for the given key.
     *
     * @return mixed The value associated with the key, or null if not found.
     */
    public function get(string $key): mixed;
}
