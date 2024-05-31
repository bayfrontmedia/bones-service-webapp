<?php

namespace Bayfront\BonesService\WebApp\Interfaces;

interface WebAppControllerInterface
{

    /**
     * Is controller private?
     *
     * This value determines which event to trigger:
     *   - webapp.controller.public
     *   - webapp.controller.private
     *
     * @return bool
     */
    public function isPrivate(): bool;

}