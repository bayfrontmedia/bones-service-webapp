# Web app service: Filters

The following [filters](https://github.com/bayfrontmedia/bones/blob/master/docs/services/filters.md) are added by this
service:

- `webapp.response.body`: Filters the response sent with the [respond](controllers.md#respond) method.
- `webapp.response.data`: Filters the data array sent to the Veil file with the [respond](controllers.md#respond)
  method.

## Filter subscribers

| Subscription (method)                   | Filter                 | Priority |
|-----------------------------------------|------------------------|----------|
| [addWebAppVersion](#addwebappversion)   | `about.bones`          | 10       |
| [addLocaleToRoutes](#addlocaletoroutes) | `router.route_prefix`  | 10       |
| [addTagRoute](#addtagroute)             | `webapp.response.body` | 10       |
| [addTagSay](#addtagsay)                 | `webapp.response.body` | 10       |
| [setWebAppData](#setwebappdata)         | `webapp.response.data` | 99       |

## addWebAppVersion

**Description:**

Add web app version to the array returned by the `php bones about:bones` console command, if existing.

**Parameters:**

- `$array` (array)

**Returns:**

- (array)

## addLocaleToRoutes

**Description:**

Add locale to route prefix if enabled.

**Parameters:**

- `$prefix` (string)

**Returns:**

- (string)

## addTagRoute

**Description:**

Add support for the `@route:` template tag which returns the URL of any named route.

**Parameters:**

- `$body` (string)

**Returns:**

- (string)

## addTagSay

**Description:**

Add support for the `@say:` template tag which returns the translation of a given string.

**Parameters:**

- `$body` (string)

**Returns:**

- (string)

## setWebAppData

**Description:**

Set web app data.

- `locale.valid`
- `locale.current`
- `webapp`: `webapp.public` config array

**Parameters:**

- `$data` (array)

**Returns:**

- (array)