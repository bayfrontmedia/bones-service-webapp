# Web app service: Initial setup

- [Dependencies](#dependencies)
- [Configuration](#configuration)
- [Add to container](#add-to-container)

## Dependencies

This service requires the following Bones services to exist in the container:

- [Router](https://github.com/bayfrontmedia/bones/blob/master/docs/services/router.md)
- [Veil](https://github.com/bayfrontmedia/bones/blob/master/docs/services/veil.md)

## Configuration

This service requires a configuration array.
Typically, this would be placed at `config/webapp.php`.

**Example:**

```php
return [
    'version' => '1.0.0' // Web app version
];
```

The configuration rules are enforced by event subscriptions and automatically added by the web app service.

The web app `version` is added to the information returned by the `php bones about:bones` [console command](https://github.com/bayfrontmedia/bones/blob/master/docs/usage/console.md).

## Add to container

With the configuration completed, the `WebAppService` class needs to be added to the Bones [service container](https://github.com/bayfrontmedia/bones/blob/master/docs/usage/container.md).
This is typically done in the `resources/bootstrap.php` file.
You may also wish to create an alias.

For more information, see [Bones bootstrap documentation](https://github.com/bayfrontmedia/bones/blob/master/docs/usage/bootstrap.md).

To ensure it only gets instantiated when needed, the container can `set` the class:

```php
use Bayfront\Bones\Application\Utilities\App;

$container->set('Bayfront\BonesService\WebApp\WebAppService', function (Container $container) {

    return $container->make('Bayfront\BonesService\WebApp\WebAppService', [
        'config' => (array)App::getConfig('webapp', [])
    ]);

});

$container->setAlias('webAppService', 'Bayfront\BonesService\WebApp\WebAppService');
```

However, by allowing the container to `make` the class during bootstrapping,
the web app service is available to be used in console commands:

```php
$webAppService = $container->make('Bayfront\BonesService\WebApp\WebAppService', [
    'config' => (array)App::getConfig('webapp', [])
]);

$container->set('Bayfront\BonesService\WebApp\WebAppService', $webAppService);
$container->setAlias('webAppService', 'Bayfront\BonesService\WebApp\WebAppService');
```