<?php

namespace Bayfront\BonesService\WebApp\Abstracts;

use Bayfront\Bones\Abstracts\Controller;
use Bayfront\BonesService\WebApp\Interfaces\WebAppControllerInterface;
use Bayfront\BonesService\WebApp\WebAppService;

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

}