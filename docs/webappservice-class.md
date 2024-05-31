# Web app service: WebAppService class

The `WebAppService` class contains the following Bones services:

- [EventService](https://github.com/bayfrontmedia/bones/blob/master/docs/services/events.md) as `$this->events`
- [FilterService](https://github.com/bayfrontmedia/bones/blob/master/docs/services/filters.md) as `$this->filters`
- [Response](https://github.com/bayfrontmedia/bones/blob/master/docs/services/response.md) as `$this->response`
- [Router](https://github.com/bayfrontmedia/bones/blob/master/docs/services/router.md) as `$this->router`
- [Veil](https://github.com/bayfrontmedia/bones/blob/master/docs/services/veil.md) as `$this->veil`

Methods include:

- [getConfig](#getconfig)
- [respond](#respond)

## getConfig

**Description**

Get web app configuration value in dot notation.

**Parameters**

- `$key = ''` (string): Key to return in dot notation
- `$default = null` (mixed): Default value to return if not existing

**Returns**

- (mixed)

## respond

**Description**

Send web app response

- Filters body using the `webapp.response.body` filter
- Filters data using the `webapp.response.data` filter
- Triggers the `webapp.response` event

**Parameters**

- `$veil_file` (string): Path to file from base path, excluding file extension
- `$data = []` (array): Data to pass to view
- `$status_code = 200` (int): HTTP status code to send
- `$headers = []` (array): Key/value pairs of header values to send

**Returns**

- (void)

**Throws**

- `Bayfront\BonesService\WebApp\Exceptions\WebAppServiceException`