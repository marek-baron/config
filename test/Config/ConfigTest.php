<?php

declare(strict_types=1);

namespace MarekBaron\Test\Config;

use MarekBaron\Config\Config;
use MarekBaron\Config\ConfigInterface;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    private ConfigInterface $config;

    protected function setUp(): void
    {
        $this->config = new Config([
            'db' => ['host' => 'localhost', 'port' => 3306],
            'app' => ['debug' => false],
            'nested' => ['a' => ['b' => ['c' => 'deep']]],
        ]);
    }

    public function testGetSimpleValue(): void
    {
        self::assertSame('localhost', $this->config->get('db.host'));
    }

    public function testGetNestedValue(): void
    {
        self::assertSame('deep', $this->config->get('nested.a.b.c'));
    }

    public function testDefaultIsReturnedIfKeyMissing(): void
    {
        self::assertSame('fallback', $this->config->get('missing.key', 'fallback'));
    }

    public function testHasDetectsExistingKeys(): void
    {
        self::assertTrue($this->config->has('db'));
        self::assertFalse($this->config->has('nope'));
    }

    public function testWithCreatesImmutableClone(): void
    {
        $new = $this->config->with('app.debug', true);

        self::assertNotSame($this->config, $new);
        self::assertFalse($this->config->get('app.debug'));
        self::assertTrue($new->get('app.debug'));
    }

    public function testIterationAndCountWork(): void
    {
        $keys = [];
        foreach ($this->config as $key => $_) {
            $keys[] = $key;
        }

        self::assertSame(['db', 'app', 'nested'], $keys);
        self::assertCount(3, $this->config);
    }

    public function testToArrayProducesExpectedArray(): void
    {
        $array = $this->config->toArray();
        self::assertSame(3306, $array['db']['port']);
        self::assertIsArray($array['nested']);
    }
}
