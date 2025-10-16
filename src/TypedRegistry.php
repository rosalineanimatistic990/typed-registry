<?php

declare(strict_types=1);

namespace TypedRegistry;

use function array_is_list;
use function get_debug_type;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_scalar;
use function is_string;
use function var_export;

/**
 * Strict, non-coercive typed facade for registry/config providers.
 *
 * Provides type-safe getters for primitives, lists, and maps with no implicit coercion.
 */
final class TypedRegistry
{
    public function __construct(private Provider $provider)
    {
    }

    // ==================== Primitive Getters ====================

    /**
     * Retrieve a string value.
     *
     * @throws RegistryTypeError if the value is not a string
     */
    public function getString(string $key): string
    {
        $value = $this->provider->get($key);
        if (!is_string($value)) {
            throw new RegistryTypeError($this->msg($key, 'string', $value));
        }

        return $value;
    }

    /**
     * Retrieve an integer value.
     *
     * @throws RegistryTypeError if the value is not an int
     */
    public function getInt(string $key): int
    {
        $value = $this->provider->get($key);
        if (!is_int($value)) {
            throw new RegistryTypeError($this->msg($key, 'int', $value));
        }

        return $value;
    }

    /**
     * Retrieve a boolean value.
     *
     * @throws RegistryTypeError if the value is not a bool
     */
    public function getBool(string $key): bool
    {
        $value = $this->provider->get($key);
        if (!is_bool($value)) {
            throw new RegistryTypeError($this->msg($key, 'bool', $value));
        }

        return $value;
    }

    /**
     * Retrieve a float value.
     *
     * @throws RegistryTypeError if the value is not a float
     */
    public function getFloat(string $key): float
    {
        $value = $this->provider->get($key);
        if (!is_float($value)) {
            throw new RegistryTypeError($this->msg($key, 'float', $value));
        }

        return $value;
    }

    // ==================== Nullable Variants ====================

    /**
     * Retrieve a string value or null.
     *
     * @throws RegistryTypeError if the value is neither string nor null
     */
    public function getNullableString(string $key): ?string
    {
        $value = $this->provider->get($key);
        if ($value === null) {
            return null;
        }
        if (!is_string($value)) {
            throw new RegistryTypeError($this->msg($key, 'string|null', $value));
        }

        return $value;
    }

    /**
     * Retrieve an integer value or null.
     *
     * @throws RegistryTypeError if the value is neither int nor null
     */
    public function getNullableInt(string $key): ?int
    {
        $value = $this->provider->get($key);
        if ($value === null) {
            return null;
        }
        if (!is_int($value)) {
            throw new RegistryTypeError($this->msg($key, 'int|null', $value));
        }

        return $value;
    }

    /**
     * Retrieve a boolean value or null.
     *
     * @throws RegistryTypeError if the value is neither bool nor null
     */
    public function getNullableBool(string $key): ?bool
    {
        $value = $this->provider->get($key);
        if ($value === null) {
            return null;
        }
        if (!is_bool($value)) {
            throw new RegistryTypeError($this->msg($key, 'bool|null', $value));
        }

        return $value;
    }

    /**
     * Retrieve a float value or null.
     *
     * @throws RegistryTypeError if the value is neither float nor null
     */
    public function getNullableFloat(string $key): ?float
    {
        $value = $this->provider->get($key);
        if ($value === null) {
            return null;
        }
        if (!is_float($value)) {
            throw new RegistryTypeError($this->msg($key, 'float|null', $value));
        }

        return $value;
    }

    // ==================== Getters with Defaults ====================

    /**
     * Retrieve a string value, or return the default if missing or type-mismatched.
     */
    public function getStringOr(string $key, string $default): string
    {
        try {
            return $this->getString($key);
        } catch (RegistryTypeError) {
            return $default;
        }
    }

    /**
     * Retrieve an integer value, or return the default if missing or type-mismatched.
     */
    public function getIntOr(string $key, int $default): int
    {
        try {
            return $this->getInt($key);
        } catch (RegistryTypeError) {
            return $default;
        }
    }

    /**
     * Retrieve a boolean value, or return the default if missing or type-mismatched.
     */
    public function getBoolOr(string $key, bool $default): bool
    {
        try {
            return $this->getBool($key);
        } catch (RegistryTypeError) {
            return $default;
        }
    }

    /**
     * Retrieve a float value, or return the default if missing or type-mismatched.
     */
    public function getFloatOr(string $key, float $default): float
    {
        try {
            return $this->getFloat($key);
        } catch (RegistryTypeError) {
            return $default;
        }
    }

    // ==================== List Getters ====================

    /**
     * Retrieve a list of strings.
     *
     * @return list<string>
     * @throws RegistryTypeError if the value is not a list or contains non-string elements
     */
    public function getStringList(string $key): array
    {
        $value = $this->provider->get($key);
        if (!is_array($value) || !array_is_list($value)) {
            throw new RegistryTypeError($this->msg($key, 'list<string>', $value));
        }

        foreach ($value as $index => $item) {
            if (!is_string($item)) {
                throw new RegistryTypeError($this->msg("{$key}[{$index}]", 'string', $item));
            }
        }

        /** @var list<string> $value */
        return $value;
    }

    /**
     * Retrieve a list of integers.
     *
     * @return list<int>
     * @throws RegistryTypeError if the value is not a list or contains non-int elements
     */
    public function getIntList(string $key): array
    {
        $value = $this->provider->get($key);
        if (!is_array($value) || !array_is_list($value)) {
            throw new RegistryTypeError($this->msg($key, 'list<int>', $value));
        }

        foreach ($value as $index => $item) {
            if (!is_int($item)) {
                throw new RegistryTypeError($this->msg("{$key}[{$index}]", 'int', $item));
            }
        }

        /** @var list<int> $value */
        return $value;
    }

    /**
     * Retrieve a list of booleans.
     *
     * @return list<bool>
     * @throws RegistryTypeError if the value is not a list or contains non-bool elements
     */
    public function getBoolList(string $key): array
    {
        $value = $this->provider->get($key);
        if (!is_array($value) || !array_is_list($value)) {
            throw new RegistryTypeError($this->msg($key, 'list<bool>', $value));
        }

        foreach ($value as $index => $item) {
            if (!is_bool($item)) {
                throw new RegistryTypeError($this->msg("{$key}[{$index}]", 'bool', $item));
            }
        }

        /** @var list<bool> $value */
        return $value;
    }

    /**
     * Retrieve a list of floats.
     *
     * @return list<float>
     * @throws RegistryTypeError if the value is not a list or contains non-float elements
     */
    public function getFloatList(string $key): array
    {
        $value = $this->provider->get($key);
        if (!is_array($value) || !array_is_list($value)) {
            throw new RegistryTypeError($this->msg($key, 'list<float>', $value));
        }

        foreach ($value as $index => $item) {
            if (!is_float($item)) {
                throw new RegistryTypeError($this->msg("{$key}[{$index}]", 'float', $item));
            }
        }

        /** @var list<float> $value */
        return $value;
    }

    // ==================== Map Getters ====================

    /**
     * Retrieve a map of string keys to string values.
     *
     * @return array<string, string>
     * @throws RegistryTypeError if the value is not an array or contains invalid key/value types
     */
    public function getStringMap(string $key): array
    {
        $value = $this->provider->get($key);
        if (!is_array($value)) {
            throw new RegistryTypeError($this->msg($key, 'map<string,string>', $value));
        }

        foreach ($value as $k => $v) {
            if (!is_string($k) || !is_string($v)) {
                throw new RegistryTypeError($this->msg($key, 'map<string,string>', $value));
            }
        }

        /** @var array<string, string> $value */
        return $value;
    }

    /**
     * Retrieve a map of string keys to integer values.
     *
     * @return array<string, int>
     * @throws RegistryTypeError if the value is not an array or contains invalid key/value types
     */
    public function getIntMap(string $key): array
    {
        $value = $this->provider->get($key);
        if (!is_array($value)) {
            throw new RegistryTypeError($this->msg($key, 'map<string,int>', $value));
        }

        foreach ($value as $k => $v) {
            if (!is_string($k) || !is_int($v)) {
                throw new RegistryTypeError($this->msg($key, 'map<string,int>', $value));
            }
        }

        /** @var array<string, int> $value */
        return $value;
    }

    /**
     * Retrieve a map of string keys to boolean values.
     *
     * @return array<string, bool>
     * @throws RegistryTypeError if the value is not an array or contains invalid key/value types
     */
    public function getBoolMap(string $key): array
    {
        $value = $this->provider->get($key);
        if (!is_array($value)) {
            throw new RegistryTypeError($this->msg($key, 'map<string,bool>', $value));
        }

        foreach ($value as $k => $v) {
            if (!is_string($k) || !is_bool($v)) {
                throw new RegistryTypeError($this->msg($key, 'map<string,bool>', $value));
            }
        }

        /** @var array<string, bool> $value */
        return $value;
    }

    /**
     * Retrieve a map of string keys to float values.
     *
     * @return array<string, float>
     * @throws RegistryTypeError if the value is not an array or contains invalid key/value types
     */
    public function getFloatMap(string $key): array
    {
        $value = $this->provider->get($key);
        if (!is_array($value)) {
            throw new RegistryTypeError($this->msg($key, 'map<string,float>', $value));
        }

        foreach ($value as $k => $v) {
            if (!is_string($k) || !is_float($v)) {
                throw new RegistryTypeError($this->msg($key, 'map<string,float>', $value));
            }
        }

        /** @var array<string, float> $value */
        return $value;
    }

    // ==================== Private Helpers ====================

    /**
     * Format a type mismatch error message.
     */
    private function msg(string $key, string $expected, mixed $got): string
    {
        $repr = is_scalar($got) || $got === null
            ? var_export($got, true)
            : get_debug_type($got);

        return "[typed-registry] key '{$key}' must be {$expected}, got {$repr}";
    }
}
