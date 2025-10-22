# marek-baron/config
[![CI](https://github.com/marek-baron/config/actions/workflows/ci.yml/badge.svg)](https://github.com/marek-baron/config/actions)


Lightweight, dependency-free PHP configuration library.

## Features

- Simple key access using dot notation (db.host)
- Immutable config (with() returns a cloned instance)
- Merge multiple config sources via ConfigAggregator
- Provider support for files, callables, classes, or arrays
- No external dependencies

## Installation
composer require marek-baron/config



## Config example

```php
$config = new Config([
'db' => ['host' => 'localhost', 'port' => 3306],
'app' => ['debug' => false],
]);

echo $config->get('db.host');
$newConfig = $config->with('app.debug', true); //Immutable!

var_dump($config->get('app.debug'));     // false
var_dump($newConfig->get('app.debug'));  // true
```

## ConfigAggregator example

```php
use MarekBaron\Config\ConfigAggregator;

$aggregator = new ConfigAggregator([
    __DIR__ . '/config/global.php',
    __DIR__ . '/config/local.php',
    fn() => ['cache' => ['enabled' => true]],
]);

$config = $aggregator->load();

echo $config->get('cache.enabled'); // true
```

ConfigProviderInterface example

```php
namespace App\Domain\Module;

use MarekBaron\Config\ConfigProviderInterface;

class ConfigProvider implements ConfigProviderInterface
{
    public function __invoke(): array
    {
        return [
            'factories' => [
                UserLoginHandler::class => UserLoginHandlerFactory::class,
            ],
            'routes' => [
                // your routes
            ],
            // anything else
        ];
    }
}

```
## Development (optional)

A Dockerfile and docker-compose.yml are included for local development.
They are excluded from Packagist via .gitattributes.

```bash
docker compose run --rm dev composer check
```

## License

MIT License © Marek Baron


[![PHPUnit](https://github.com/marek-baron/config/actions/workflows/ci.yml/badge.svg)](https://github.com/marek-baron/config/actions)
[![Packagist](https://img.shields.io/packagist/v/marek-baron/config.svg)](https://packagist.org/packages/marek-baron/config)
[![License](https://img.shields.io/github/license/marek-baron/config.svg)](LICENSE)
