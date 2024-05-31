# Web app service

- [Initial setup](setup.md)
- [Events](events.md)
- [Filters](filters.md)
- [WebAppService](webappservice-class.md)

## General usage

### Controllers

Besides events and filters, the web app service interaction happens within controllers.

Any controller used by the web app service must extend `Bayfront\BonesService\WebApp\Abstracts\WebAppController`,
which implements an `Bayfront\BonesService\WebApp\Interfaces\WebAppControllerInterface`.

The interface requires only one method, `isPrivate`, which returns a boolean value.
its value determines which of the web app controller events are executed.

The [WebAppService class](webappservice-class.md) is available within the controller as `$this->webAppService`.

### Views

This service adds support for the `@route:` tag from within Veil views,
which returns the URL of any named route.

For example, if a named route of `home` exists, adding `@route:home` from within a Veil view file
will render the URL of the named route.