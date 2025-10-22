<?php

/**
 * Author: Marek Baron
 * GitHub: https://www.github.com/marek-baron
 * Project: marek-baron/config
 */

declare(strict_types=1);

namespace MarekBaron\Config;

/** @phpstan-consistent-constructor */
class Config implements ConfigInterface
{
    private array $config = [];

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function get(string $name, mixed $default = null): mixed
    {
        if (empty($name)) {
            return $this->config;
        }

        if (str_contains($name, '.')) {
            $keys = explode('.', $name);
            $value = $this->config;

            foreach ($keys as $segment) {
                if (!is_array($value) || !array_key_exists($segment, $value)) {
                    return $default;
                }

                $value = $value[$segment];
            }

            return $value;
        }

        if (array_key_exists($name, $this->config)) {
            return $this->config[$name] ?? $default;
        }

        return $this->config;
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->config);
    }


    public function with(string $key, mixed $value): self
    {
        $clone = clone $this;
        $this->setNestedValue($clone->config, $key, $value);
        return $clone;
    }

    public function current(): mixed
    {
        return current($this->config);
    }

    public function next(): void
    {
        next($this->config);
    }

    public function key(): string|int|null
    {
        return key($this->config);
    }

    public function valid(): bool
    {
        return ($this->key() !== null);
    }

    public function rewind(): void
    {
        reset($this->config);
    }

    public function count(): int
    {
        return count($this->config);
    }

    public function toArray(): array
    {
        $result = [];
        $config = $this->config;

        foreach ($config as $key => $value) {
            if ($value instanceof Config) {
                $result[$key] = $value->toArray();
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * @param array<string,mixed> $array
     */
    private function setNestedValue(array &$array, string $path, mixed $value): void
    {
        $segments = explode('.', $path);
        $ref = &$array;

        foreach ($segments as $seg) {
            if (!isset($ref[$seg]) || !is_array($ref[$seg])) {
                $ref[$seg] = [];
            }
            $ref = &$ref[$seg];
        }

        $ref = $value;
    }
}
