# Web app service: Events

The following [events](https://github.com/bayfrontmedia/bones/blob/master/docs/services/events.md) are added by this service:

- `webapp.start`: Executes in the `WebAppService` constructor as the first event available to this service. The `WebAppService` instance is passed as a parameter.
- `webapp.controller`: Executes when any `WebAppController` is instantiated. The controller is passed as a parameter.
- `webapp.controller.public`: Executes when an `WebAppController` is not private. The controller is passed as a parameter.
- `webapp.controller.private`: Executes when an `WebAppController` is private. The controller is passed as a parameter.
- `webapp.response`: Executes just before the response is sent with the [respond](webappservice-class.md#respond) method. The `Bayfront\HttpResponse\Response` class is passed as a parameter.