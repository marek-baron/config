<?php

/**
 * Author: Marek Baron
 * GitHub: https://www.github.com/marek-baron
 * Project: marek-baron/config
 */

declare(strict_types=1);

namespace MarekBaron\Config;

use InvalidArgumentException;

final class ConfigAggregator
{
    /** @var array<string, callable|string|array> */
    private array $providers;

    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    public function load(): ConfigInterface
    {
        $merged = [];

        foreach ($this->providers as $provider) {
            $config = match (true) {
                is_callable($provider) => $provider(),
                is_string($provider) && file_exists($provider) => require_once $provider,
                is_string($provider) && class_exists($provider) => (new $provider())(),
                is_array($provider) => $provider,
                default => throw new InvalidArgumentException(
                    sprintf('Invalid config provider of type: %s', get_debug_type($provider))
                )
            };

            if (!is_array($config)) {
                throw new InvalidArgumentException('Config provider must return an array!');
            }

            $merged = array_replace_recursive($merged, $config);
        }

        return new Config($merged);
    }
}
