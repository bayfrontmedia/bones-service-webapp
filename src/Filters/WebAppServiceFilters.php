<?php

namespace Bayfront\BonesService\WebApp\Filters;

use Bayfront\Bones\Abstracts\FilterSubscriber;
use Bayfront\Bones\Application\Services\Filters\FilterSubscription;
use Bayfront\Bones\Application\Utilities\App;
use Bayfront\Bones\Interfaces\FilterSubscriberInterface;
use Bayfront\BonesService\WebApp\Utilities\VeilData;
use Bayfront\BonesService\WebApp\WebAppService;
use Bayfront\Cookies\Cookie;
use Bayfront\Translation\Translate;
use Bayfront\Translation\TranslationException;

class WebAppServiceFilters extends FilterSubscriber implements FilterSubscriberInterface
{

    protected WebAppService $webAppService;
    protected Translate $translate;

    public function __construct(WebAppService $webAppService, Translate $translate)
    {
        $this->webAppService = $webAppService;
        $this->translate = $translate;
    }

    /**
     * @inheritDoc
     */
    public function getSubscriptions(): array
    {

        return [
            new FilterSubscription('about.bones', [$this, 'addWebAppVersion'], 10),
            new FilterSubscription('router.route_prefix', [$this, 'addLocaleToRoutes'], 10),
            new FilterSubscription('webapp.response.body', [$this, 'addTagRoute'], 10),
            new FilterSubscription('webapp.response.body', [$this, 'addTagSay'], 10),
            new FilterSubscription('webapp.response.data', [$this, 'setWebAppData'], 99)
        ];

    }

    /**
     * Add web app version to the array returned by the php bones about:bones console command, if existing.
     *
     * @param array $arr
     * @return array
     */

    public function addWebAppVersion(array $arr): array
    {
        if (App::getConfig('webapp.public.version') === null) {
            return $arr;
        }

        return array_merge($arr, [
            'Web app version' => App::getConfig('webapp.public.version')
        ]);
    }

    /**
     * Add locale to route prefix if enabled.
     *
     * @param string $prefix
     * @return string
     */

    public function addLocaleToRoutes(string $prefix): string
    {

        if (App::getConfig('webapp.locale.enabled') !== true) {
            return $prefix;
        }

        $cookie_name = App::getConfig('webapp.locale.cookie.name', 'locale');

        if (App::getConfig('webapp.locale.routes.redirect')
            && Cookie::has($cookie_name)
            && in_array(Cookie::get($cookie_name), App::getConfig('webapp.locale.valid', []))) {

            return rtrim($prefix, '/') . '/' . Cookie::get($cookie_name) . '/';

        }

        return $prefix;

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

    /**
     * Add support for the @say: template tag which returns the translation of a given string.
     *
     * @param string $body
     * @return string
     */

    public function addTagSay(string $body): string
    {

        // @say

        preg_match_all("/@say:[\w.]+/", $body, $tags); // Any word character or period

        if (isset($tags[0]) && is_array($tags[0])) { // If a tag was found

            try {

                foreach ($tags[0] as $tag) {

                    $use = explode(':', $tag, 2);

                    if (isset($use[1])) { // If valid @say syntax

                        // Keep original string if not found

                        $body = str_replace($tag, $this->translate->get($use[1], [], $use[1]), $body);

                    }
                }

            } catch (TranslationException) {

                /*
                 * No translation exists for this tag.
                 * Do nothing.
                 */

            }

        }

        return $body;

    }

    /**
     * Add web app data.
     *
     * - locale.valid
     * - locale.current
     * - webapp (webapp.public config array)
     *
     * @param array $data
     * @return array
     */
    public function setWebAppData(array $data): array
    {
        $data = array_merge($data, [
            'locale' => [
                'valid' => App::getConfig('webapp.locale.valid', []),
                'current' => $this->translate->getLocale()
            ],
            'webapp' => App::getConfig('app.webapp.public', [])
        ]);

        VeilData::set($data);
        return $data;
    }

}