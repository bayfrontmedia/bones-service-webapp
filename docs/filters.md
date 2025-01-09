# Web app service: Filters

The following [filters](https://github.com/bayfrontmedia/bones/blob/master/docs/services/filters.md) are added by this
service:

- `webapp.response.body`: Filters the response sent with the [respond](controllers.md#respond) method.
- `webapp.response.data`: Filters the data array sent to the Veil file with the [respond](controllers.md#respond)
  method.

## Filter subscribers

| Subscription (method)                   | Filter                 | Priority |
|-----------------------------------------|------------------------|----------|
| [addLocaleToRoutes](#addlocaletoroutes) | `router.route_prefix`  | 10       |
| [addTagRoute](#addtagroute)             | `webapp.response.body` | 10       |
| [addTagSay](#addtagsay)                 | `webapp.response.body` | 10       |
| [setWebAppData](#setwebappdata)         | `webapp.response.data` | 99       |

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

Set web app data for use in Veil templates.

- `app.version`: As defined at `app.version` config array, if existing
- `locale.current`
- `locale.valid`
- `webapp`: `webapp.public` config array

**Parameters:**

- `$data` (array)

**Returns:**

- (array)