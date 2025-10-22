<?php

declare(strict_types=1);

namespace MarekBaron\Test\Config;

use MarekBaron\Config\ConfigProviderInterface;
use PHPUnit\Framework\TestCase;

class ConfigProviderInterfaceTest extends TestCase
{
    public function testAnonymousProviderReturnsArray(): void
    {
        $provider = new class implements ConfigProviderInterface {
            public function __invoke(?string $key = null): array
            {
                return ['routes' => [], 'factories' => []];
            }
        };

        $result = $provider();
        self::assertIsArray($result);
        self::assertArrayHasKey('routes', $result);
    }
}
