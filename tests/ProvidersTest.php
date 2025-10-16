<?php

declare(strict_types=1);

namespace TypedRegistry\Tests;

use PHPUnit\Framework\TestCase;
use TypedRegistry\ArrayProvider;
use TypedRegistry\CallbackProvider;
use TypedRegistry\CompositeProvider;

final class ProvidersTest extends TestCase
{
    // ==================== ArrayProvider ====================

    public function testArrayProviderReturnsExistingValue(): void
    {
        $provider = new ArrayProvider(['key' => 'value', 'number' => 42]);

        self::assertSame('value', $provider->get('key'));
        self::assertSame(42, $provider->get('number'));
    }

    public function testArrayProviderReturnsNullForMissingKey(): void
    {
        $provider = new ArrayProvider(['key' => 'value']);

        self::assertNull($provider->get('missing'));
    }

    public function testArrayProviderCanStoreNull(): void
    {
        $provider = new ArrayProvider(['key' => null]);

        self::assertNull($provider->get('key'));
    }

    // ==================== CallbackProvider ====================

    public function testCallbackProviderInvokesCallable(): void
    {
        $provider = new CallbackProvider(fn(string $key): mixed => match ($key) {
            'foo' => 'bar',
            'num' => 123,
            default => null,
        });

        self::assertSame('bar', $provider->get('foo'));
        self::assertSame(123, $provider->get('num'));
        self::assertNull($provider->get('missing'));
    }

    public function testCallbackProviderPassesKeyToCallable(): void
    {
        $capturedKey = null;
        $provider = new CallbackProvider(function (string $key) use (&$capturedKey): mixed {
            $capturedKey = $key;
            return 'result';
        });

        $provider->get('test-key');

        self::assertSame('test-key', $capturedKey);
    }

    // ==================== CompositeProvider ====================

    public function testCompositeProviderReturnsFromFirstProvider(): void
    {
        $provider = new CompositeProvider([
            new ArrayProvider(['key' => 'first']),
            new ArrayProvider(['key' => 'second']),
        ]);

        self::assertSame('first', $provider->get('key'));
    }

    public function testCompositeProviderFallsBackToSecondProvider(): void
    {
        $provider = new CompositeProvider([
            new ArrayProvider(['other' => 'value']),
            new ArrayProvider(['key' => 'second']),
        ]);

        self::assertSame('second', $provider->get('key'));
    }

    public function testCompositeProviderReturnsNullWhenAllProvidersReturnNull(): void
    {
        $provider = new CompositeProvider([
            new ArrayProvider(['other1' => 'value']),
            new ArrayProvider(['other2' => 'value']),
        ]);

        self::assertNull($provider->get('missing'));
    }

    public function testCompositeProviderSkipsNullValues(): void
    {
        $provider = new CompositeProvider([
            new ArrayProvider(['key' => null]),
            new ArrayProvider(['key' => 'second']),
        ]);

        // First provider returns null explicitly, so composite should return from second
        self::assertSame('second', $provider->get('key'));
    }

    public function testCompositeProviderWithCallbackProvider(): void
    {
        $provider = new CompositeProvider([
            new ArrayProvider(['key1' => 'array-value']),
            new CallbackProvider(fn(string $k): mixed => $k === 'key2' ? 'callback-value' : null),
        ]);

        self::assertSame('array-value', $provider->get('key1'));
        self::assertSame('callback-value', $provider->get('key2'));
    }

    public function testCompositeProviderWithEmptyProviderList(): void
    {
        $provider = new CompositeProvider([]);

        self::assertNull($provider->get('any-key'));
    }

    public function testCompositeProviderPrioritizesEarlierProviders(): void
    {
        // Simulates environment overrides -> config file -> defaults pattern
        $defaults = new ArrayProvider(['debug' => false, 'port' => 8080]);
        $config = new ArrayProvider(['debug' => true]);
        $env = new ArrayProvider(['port' => 3000]);

        $provider = new CompositeProvider([$env, $config, $defaults]);

        // From env
        self::assertSame(3000, $provider->get('port'));
        // From config
        self::assertTrue($provider->get('debug'));
    }
}
