<?php /** @noinspection PhpUnused */

namespace Bayfront\BonesService\WebApp;

use Bayfront\Bones\Abstracts\Service;
use Bayfront\Bones\Application\Services\Events\EventService;
use Bayfront\Bones\Application\Services\Filters\FilterService;
use Bayfront\Bones\Exceptions\ServiceException;
use Bayfront\BonesService\WebApp\Events\WebAppServiceEvents;
use Bayfront\BonesService\WebApp\Exceptions\WebAppServiceException;
use Bayfront\BonesService\WebApp\Filters\WebAppServiceFilters;
use Bayfront\HttpResponse\Response;
use Bayfront\RouteIt\Router;
use Bayfront\Translation\Translate;
use Bayfront\Veil\Veil;

class WebAppService extends Service
{

    public EventService $events;
    public FilterService $filters;
    public Response $response;
    public Router $router;
    public Translate $translate;
    public Veil $veil;

    /**
     * The container will resolve any dependencies.
     * EventService is required by the abstract service.
     *
     * @param EventService $events
     * @param FilterService $filters
     * @param Response $response
     * @param Router $router
     * @param Translate $translate
     * @param Veil $veil
     * @throws WebAppServiceException
     */

    public function __construct(EventService $events, FilterService $filters, Response $response, Router $router, Translate $translate, Veil $veil)
    {
        $this->events = $events;
        $this->filters = $filters;
        $this->response = $response;
        $this->router = $router;
        $this->translate = $translate;
        $this->veil = $veil;

        parent::__construct($events);

        // Enqueue events

        try {
            $this->events->addSubscriptions(new WebAppServiceEvents($this, $translate));
        } catch (ServiceException $e) {
            throw new WebAppServiceException('Unable to start WebAppService: ' . $e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        // Enqueue filters

        try {
            $this->filters->addSubscriptions(new WebAppServiceFilters($this, $translate));
        } catch (ServiceException $e) {
            throw new WebAppServiceException('Unable to start WebAppService: ' . $e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        $this->events->doEvent('webapp.start', $this);

    }

}