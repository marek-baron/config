<?php

declare(strict_types=1);

namespace MarekBaron\Test\Config\Fixture;

use MarekBaron\Config\ConfigProviderInterface;

class DummyProvider implements ConfigProviderInterface
{
    public function __invoke(?string $key = null): array
    {
        return ['foo' => 'bar'];
    }
}
