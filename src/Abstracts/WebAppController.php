<?php /** @noinspection PhpUnused */

namespace Bayfront\BonesService\WebApp\Abstracts;

use Bayfront\Bones\Abstracts\Controller;
use Bayfront\BonesService\WebApp\Exceptions\WebAppServiceException;
use Bayfront\BonesService\WebApp\Interfaces\WebAppControllerInterface;
use Bayfront\BonesService\WebApp\WebAppService;
use Bayfront\HttpResponse\InvalidStatusCodeException;
use Bayfront\Veil\FileNotFoundException;

abstract class WebAppController extends Controller implements WebAppControllerInterface
{

    protected WebAppService $webAppService;

    public function __construct(WebAppService $webAppService)
    {

        $this->webAppService = $webAppService;

        parent::__construct($this->webAppService->events); // Fires the bones.controller event

        $this->webAppService->events->doEvent('webapp.controller', $this);

        if ($this->isPrivate()) {
            $this->webAppService->events->doEvent('webapp.controller.private', $this);
        } else {
            $this->webAppService->events->doEvent('webapp.controller.public', $this);
        }

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
    protected function respond(string $veil_file, array $data = [], int $status_code = 200, array $headers = []): void
    {

        try {

            $body = $this->webAppService->filters->doFilter('webapp.response.body', $this->webAppService->veil->getView($veil_file, $this->webAppService->filters->doFilter('webapp.response.data', $data)));

            $this->webAppService->response->setStatusCode($status_code)->setHeaders($headers)->setBody($body);

            $this->webAppService->events->doEvent('webapp.response', $this->webAppService->response);

            $this->webAppService->response->send();

        } catch (FileNotFoundException|InvalidStatusCodeException $e) {
            throw new WebAppServiceException($e->getMessage(), $e->getCode(), $e);
        }

    }

}