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
<?php

/*
 * For more information, see:
 * https://github.com/bayfrontmedia/bones-service-webapp/blob/master/docs/setup.md#configuration
 *
 * NOTE:
 * This entire array is added to the Veil data array with key of "webapp"
 */

return [
    'version' => '1.0.0', // Web app version
];
```

The configuration rules are enforced by event subscriptions and automatically added by the web app service.

The web app `version` is added to the information returned by the `php bones about:bones` [console command](https://github.com/bayfrontmedia/bones/blob/master/docs/usage/console.md).

The entire `webapp` configuration array is available within the Veil data array with key of `webapp`.

## Add to container

With the configuration completed, the `WebAppService` class needs to be added to the Bones [service container](https://github.com/bayfrontmedia/bones/blob/master/docs/usage/container.md).
This is typically done in the `resources/bootstrap.php` file.
You may also wish to create an alias.

For more information, see [Bones bootstrap documentation](https://github.com/bayfrontmedia/bones/blob/master/docs/usage/bootstrap.md).

```php
$webAppService = $container->make('Bayfront\BonesService\WebApp\WebAppService', [
    'config' => (array)App::getConfig('webapp', [])
]);

$container->set('Bayfront\BonesService\WebApp\WebAppService', $webAppService);
$container->setAlias('webAppService', 'Bayfront\BonesService\WebApp\WebAppService');
```