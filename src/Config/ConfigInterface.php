<?php

/**
 * Author: Marek Baron
 * GitHub: https://www.github.com/marek-baron
 * Project: marek-baron/config
 */

declare(strict_types=1);

namespace MarekBaron\Config;

use Countable;
use Iterator;

/** @phpstan-consistent-constructor */
interface ConfigInterface extends Countable, Iterator
{
    public function get(string $name, mixed $default = null): mixed;
    public function has(string $name): bool;
    public function toArray(): array;
    public function with(string $key, mixed $value): self;
}
