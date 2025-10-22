<?php

declare(strict_types=1);

namespace MarekBaron\Test\Config;

use InvalidArgumentException;
use MarekBaron\Config\ConfigAggregator;
use MarekBaron\Config\ConfigInterface;
use MarekBaron\Test\Config\Fixture\DummyProvider;
use PHPUnit\Framework\TestCase;

class ConfigAggregatorTest extends TestCase
{
    private string $tmpDir;

    protected function setUp(): void
    {
        $this->tmpDir = __DIR__ . '/fixtures';
        if (!is_dir($this->tmpDir)) {
            mkdir($this->tmpDir, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        if (is_dir($this->tmpDir)) {
            array_map('unlink', glob($this->tmpDir . '/*.php'));
            rmdir($this->tmpDir);
        }
    }

    public function testMergesFileCallableAndArrayProviders(): void
    {
        $file1 = $this->tmpDir . '/a.php';
        $file2 = $this->tmpDir . '/b.php';
        file_put_contents($file1, "<?php return ['a' => ['x' => 1]];");
        file_put_contents($file2, "<?php return ['a' => ['y' => 2]];");

        $aggregator = new ConfigAggregator([
            $file1,
            $file2,
            fn() => ['b' => 3],
            ['c' => 4],
        ]);

        $config = $aggregator->load();

        self::assertInstanceOf(ConfigInterface::class, $config);
        self::assertSame(1, $config->get('a.x'));
        self::assertSame(2, $config->get('a.y'));
        self::assertSame(3, $config->get('b'));
        self::assertSame(4, $config->get('c'));
    }

    public function testClassProviderIsInvoked(): void
    {
        $aggregator = new ConfigAggregator([DummyProvider::class]);
        $config = $aggregator->load();

        self::assertSame('bar', $config->get('foo'));
    }

    public function testThrowsForInvalidProvider(): void
    {
        $aggregator = new ConfigAggregator([123]);
        $this->expectException(InvalidArgumentException::class);
        $aggregator->load();
    }

    public function testThrowsIfProviderReturnsNonArray(): void
    {
        $file = $this->tmpDir . '/invalid.php';
        file_put_contents($file, "<?php return 'nope';");

        $aggregator = new ConfigAggregator([$file]);
        $this->expectException(InvalidArgumentException::class);
        $aggregator->load();
    }
}
