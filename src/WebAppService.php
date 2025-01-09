<?php /** @noinspection PhpUnused */

namespace Bayfront\BonesService\WebApp;

use Bayfront\Bones\Abstracts\Service;
use Bayfront\Bones\Application\Services\Events\EventService;
use Bayfront\Bones\Application\Services\Filters\FilterService;
use Bayfront\Bones\Application\Utilities\App;
use Bayfront\Bones\Exceptions\ServiceException;
use Bayfront\BonesService\WebApp\Events\WebAppServiceEvents;
use Bayfront\BonesService\WebApp\Exceptions\WebAppServiceException;
use Bayfront\BonesService\WebApp\Filters\WebAppServiceFilters;
use Bayfront\Container\NotFoundException;
use Bayfront\HttpResponse\Response;
use Bayfront\RouteIt\Router;
use Bayfront\Veil\Veil;

class WebAppService extends Service
{

    public EventService $events;
    public FilterService $filters;
    public Response $response;
    public Router $router;
    public Veil $veil;

    /**
     * The container will resolve any dependencies.
     * EventService is required by the abstract service.
     *
     * @param EventService $events
     * @param FilterService $filters
     * @param Response $response
     * @param Router $router
     * @param Veil $veil
     * @throws WebAppServiceException
     */

    public function __construct(EventService $events, FilterService $filters, Response $response, Router $router, Veil $veil)
    {
        $this->events = $events;
        $this->filters = $filters;
        $this->response = $response;
        $this->router = $router;
        $this->veil = $veil;

        parent::__construct($events);

        if (!App::has('Bayfront\Translation\Translate')) {
            throw new WebAppServiceException('Unable to start WebAppService: Required dependency not found (Bayfront\Translation\Translate)');
        }

        // Enqueue events

        try {
            $translate = App::get('Bayfront\Translation\Translate');
            $this->events->addSubscriptions(new WebAppServiceEvents($this, $translate));
        } catch (ServiceException|NotFoundException $e) {
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