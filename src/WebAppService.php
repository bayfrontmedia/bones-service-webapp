<?php /** @noinspection PhpUnused */

namespace Bayfront\BonesService\WebApp;

use Bayfront\ArrayHelpers\Arr;
use Bayfront\Bones\Abstracts\Service;
use Bayfront\Bones\Application\Services\Events\EventService;
use Bayfront\Bones\Application\Services\Filters\FilterService;
use Bayfront\Bones\Exceptions\ServiceException;
use Bayfront\BonesService\WebApp\Events\WebAppServiceEvents;
use Bayfront\BonesService\WebApp\Exceptions\WebAppServiceException;
use Bayfront\BonesService\WebApp\Filters\WebAppServiceFilters;
use Bayfront\HttpResponse\InvalidStatusCodeException;
use Bayfront\HttpResponse\Response;
use Bayfront\RouteIt\Router;
use Bayfront\Veil\FileNotFoundException;
use Bayfront\Veil\Veil;

class WebAppService extends Service
{

    public EventService $events;
    public FilterService $filters;
    public Response $response;
    public Router $router;
    public Veil $veil;
    protected array $config;

    /**
     * The container will resolve any dependencies.
     * EventService is required by the abstract service.
     *
     * @param EventService $events
     * @param FilterService $filters
     * @param Response $response
     * @param Router $router
     * @param Veil $veil
     * @param array $config
     * @throws WebAppServiceException
     */

    public function __construct(EventService $events, FilterService $filters, Response $response, Router $router, Veil $veil, array $config)
    {
        $this->events = $events;
        $this->filters = $filters;
        $this->response = $response;
        $this->router = $router;
        $this->veil = $veil;
        $this->config = $config;

        parent::__construct($events);

        // Enqueue events

        try {
            $this->events->addSubscriptions(new WebAppServiceEvents($this));
        } catch (ServiceException $e) {
            throw new WebAppServiceException('Unable to start WebAppService: ' . $e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        // Enqueue filters

        try {
            $this->filters->addSubscriptions(new WebAppServiceFilters($this));
        } catch (ServiceException $e) {
            throw new WebAppServiceException('Unable to start WebAppService: ' . $e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        $this->events->doEvent('webapp.start', $this);

    }

    /**
     * Get web app configuration value in dot notation.
     *
     * @param string $key (Key to return in dot notation)
     * @param mixed|null $default (Default value to return if not existing)
     * @return mixed
     */
    public function getConfig(string $key = '', mixed $default = null): mixed
    {
        return Arr::get($this->config, $key, $default);
    }

    /**
     * Send web app response.
     *
     * - Filters body using the webapp.response.body filter
     * - Filters data using the webapp.response.data filter
     * - Triggers the webapp.response event
     *
     * @param string $veil_file (Path to file from base path, excluding file extension)
     * @param array $data (Data to pass to view)
     * @param int $status_code (HTTP status code to send)
     * @param array $headers (Key/value pairs of header values to send)
     * @return void
     * @throws WebAppServiceException
     */
    public function respond(string $veil_file, array $data = [], int $status_code = 200, array $headers = []): void
    {

        try {

            $body = $this->filters->doFilter('webapp.response.body', $this->veil->getView($veil_file, $this->filters->doFilter('webapp.response.data', $data)));

            $this->events->doEvent('webapp.response', $this->response);

            $this->response->setStatusCode($status_code)->setHeaders($headers)->setBody($body)->send();

        } catch (FileNotFoundException|InvalidStatusCodeException $e) {
            throw new WebAppServiceException($e->getMessage(), $e->getCode(), $e);
        }

    }

}