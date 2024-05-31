<?php

namespace Bayfront\BonesService\WebApp\Events;

use Bayfront\Bones\Abstracts\EventSubscriber;
use Bayfront\Bones\Interfaces\EventSubscriberInterface;
use Bayfront\BonesService\WebApp\WebAppService;

class WebAppServiceEvents extends EventSubscriber implements EventSubscriberInterface
{

    protected WebAppService $webAppService;

    public function __construct(WebAppService $webAppService)
    {
        $this->webAppService = $webAppService;
    }

    /**
     * @inheritDoc
     */
    public function getSubscriptions(): array
    {
        return [];
    }

}