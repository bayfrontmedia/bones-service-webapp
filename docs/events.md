# Web app service: Events

The following [events](https://github.com/bayfrontmedia/bones/blob/master/docs/services/events.md) are added by this
service:

- `webapp.start`: Executes in the `WebAppService` constructor as the first event available to this service.
  The `WebAppService` instance is passed as a parameter.
- `webapp.controller`: Executes when any `WebAppController` is instantiated. The controller is passed as a parameter.
- `webapp.controller.public`: Executes when an `WebAppController` is not private. The controller is passed as a
  parameter.
- `webapp.controller.private`: Executes when an `WebAppController` is private. The controller is passed as a parameter.
- `webapp.response`: Executes just before the response is sent with the [respond](controllers.md#respond) method.
  The `Bayfront\HttpResponse\Response` class is passed as a parameter.

Event subscribers:

| Subscription (method)           | Event      | Priority |
|---------------------------------|------------|----------|
| [processLocale](#processlocale) | `app.http` | 10       |

## processLocale

**Description:**

Set locale based on URL locale query parameter or cookie, and redirect if needed.

This functionality is skipped when any of the following exist:

- The `webapp.locale.enabled` [config value](setup.md#configuration) is `false`
- The host, path or route parameter matches anything defined in the `webapp.locale.routes.exclude` config array

The locale is defined by the following, when the value exists in the `webapp.locale.valid` config array:

- A cookie with the name defined in `webapp.locale.cookie.name`
- A URL query parameter with the name defined in `webapp.locale.cookie.name`
- The first segment of the URL request path

Once the locale is determined, a cookie is set with a valid duration as set in the `webapp.locale.cookie.duration` config value.
If the `webapp.locale.routes.redirect` config value is `true` and the locale does not exist as the first segment of the
URL request path, the requested page will be redirected with a `302` HTTP status.

**Parameters:**

- (none)

**Returns:**

- (void)

**Throws:**

- `Bayfront\HttpResponse\InvalidStatusCodeException`