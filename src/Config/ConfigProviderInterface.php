<?php

/**
 * Author: Marek Baron
 * GitHub: https://www.github.com/marek-baron
 * Project: marek-baron/config
 */

declare(strict_types=1);

namespace MarekBaron\Config;

interface ConfigProviderInterface
{
    /**
     * Returns a configuration array.
     *
     * @return array<string,mixed>
     */
    public function __invoke(?string $key = null): array;
}
