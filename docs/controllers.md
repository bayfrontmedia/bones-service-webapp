# Web app service: Controllers

Any controller used by the web app service must extend `Bayfront\BonesService\WebApp\Abstracts\WebAppController`,
which implements a `Bayfront\BonesService\WebApp\Interfaces\WebAppControllerInterface`.

The interface requires only one method, `isPrivate`, which returns a boolean value.
its value determines which of the web app controller events are executed.

The [WebAppService class](webappservice-class.md) is available within the controller as `$this->webAppService`.

Methods:

- [respond](#respond)

## respond

**Description:**

Send web app response.

- Filters body using the `webapp.response.body` [filter](filters.md)
- Filters data using the `webapp.response.data` [filter](filters.md)
- Triggers the `webapp.response` [event](events.md)

**Parameters:**

- `$veil_file` (string): Path to file from base path, excluding file extension
- `$data` (array): Data to pass to view
- `$status_code = 200` (int): HTTP status code to send
- `$headers = []` (array): Key/value pairs of header values to send

**Returns:**

- (void)

**Throws:**

- `Bayfront\BonesService\WebApp\Exceptions\WebAppServiceException`