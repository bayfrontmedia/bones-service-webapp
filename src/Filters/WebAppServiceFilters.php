<?php

namespace Bayfront\BonesService\WebApp\Filters;

use Bayfront\Bones\Abstracts\FilterSubscriber;
use Bayfront\Bones\Application\Services\Filters\FilterSubscription;
use Bayfront\Bones\Interfaces\FilterSubscriberInterface;
use Bayfront\BonesService\WebApp\WebAppService;

class WebAppServiceFilters extends FilterSubscriber implements FilterSubscriberInterface
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

        return [
            new FilterSubscription('about.bones', [$this, 'addWebAppVersion'], 10),
            new FilterSubscription('webapp.response.body', [$this, 'addTagRoute'], 10)
        ];

    }

    /**
     * Add web app version to the array returned by the php bones about:bones console command.
     *
     * @param array $arr
     * @return array
     */

    public function addWebAppVersion(array $arr): array
    {
        return array_merge($arr, [
            'Web app version' => $this->webAppService->getConfig('version', '')
        ]);
    }

    /**
     * Add support for the @route: template tag which returns the URL of any named route.
     *
     * @param string $body
     * @return string
     */

    public function addTagRoute(string $body): string
    {

        // @route

        preg_match_all("/@route:[\w.]+/", $body, $tags); // Any word character or period

        if (isset($tags[0]) && is_array($tags[0])) { // If a tag was found

            foreach ($tags[0] as $tag) {

                $use = explode(':', $tag, 2);

                if (isset($use[1])) { // If valid @route syntax

                    // Keep original string if not found

                    $body = str_replace($tag, $this->webAppService->router->getNamedRoute($use[1], $use[1]), $body);

                }
            }

        }

        return $body;

    }

}