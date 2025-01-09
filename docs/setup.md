# Web app service: Initial setup

- [Dependencies](#dependencies)
- [Configuration](#configuration)
- [Route prefix filter](#route-prefix-filter)
- [Add to container](#add-to-container)

## Dependencies

This service requires the following Bones services to exist in the container:

- [Router](https://github.com/bayfrontmedia/bones/blob/master/docs/services/router.md)
- [Veil](https://github.com/bayfrontmedia/bones/blob/master/docs/services/veil.md)

In addition, this service also requires the following library to exist in the container:

- [Translate](https://github.com/bayfrontmedia/translation)

The default locale used in the `Translate` class constructor should be set 
to the `webapp.locale.default` config value (see below).

## Configuration

This service requires a configuration array.
Typically, this would be placed at `config/webapp.php`.

**Example:**

```php
<?php

/*
 * For more information, see:
 * https://github.com/bayfrontmedia/bones-service-webapp/blob/master/docs/setup.md#configuration
 */

return [
    'locale' => [ // Locale settings
        'enabled' => true,
        'valid' => [ // Valid locales for which translations exist
            'en',
            'es'
        ],
        'default' => 'en', // Default locale
        'cookie' => [
            'name' => 'locale', // Cookie name
            'duration' => 43200 // Cookie duration (in minutes): 43200 = 30 days
        ],
        'routes' => [
            'redirect' => true, // Add locale to routes?
            'exclude' => [ // Excluded requests from locale processing
                'hosts' => [], // Hosts
                'paths' => [ // URL paths
                    '/api'
                ],
                'param' => 'locale_exclude' // Route parameter
            ]
        ]
    ],
    'public' => [ // Added to Veil data array with key of "webapp"
        'version' => '1.0.0', // Web app version
    ]
];
```

The `public.version` is added to the information returned by the `php bones about:bones` [console command](https://github.com/bayfrontmedia/bones/blob/master/docs/usage/console.md).

The entire `public` configuration array is available within the Veil data array with key of `webapp`.
This is helpful for any data which should automatically be available from within Veil views, such as brand information,
third-party URL's, etc.

## Route prefix filter

The web app service depends on a `router.route_prefix` filter to be applied to the route prefix in order to
add the locale to routes.

Example:

```php
$router->setRoutePrefix($this->filter->doFilter('router.route_prefix', App::getConfig('router.route_prefix')));
```

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