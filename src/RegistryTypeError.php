<?php

declare(strict_types=1);

namespace TypedRegistry;

use RuntimeException;

/**
 * Thrown when a registry value does not match the expected type.
 */
final class RegistryTypeError extends RuntimeException
{
}
