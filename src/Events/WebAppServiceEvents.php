<?php

namespace Bayfront\BonesService\WebApp\Events;

use Bayfront\ArrayHelpers\Arr;
use Bayfront\Bones\Abstracts\EventSubscriber;
use Bayfront\Bones\Application\Services\Events\EventSubscription;
use Bayfront\Bones\Application\Utilities\App;
use Bayfront\Bones\Interfaces\EventSubscriberInterface;
use Bayfront\BonesService\WebApp\WebAppService;
use Bayfront\Cookies\Cookie;
use Bayfront\HttpRequest\Request;
use Bayfront\HttpResponse\InvalidStatusCodeException;
use Bayfront\HttpResponse\Response;
use Bayfront\RouteIt\DispatchException;
use Bayfront\RouteIt\Router;
use Bayfront\Translation\Translate;
use JetBrains\PhpStorm\NoReturn;

class WebAppServiceEvents extends EventSubscriber implements EventSubscriberInterface
{

    protected WebAppService $webAppService;
    protected Router $router;
    protected Response $response;
    protected Translate $translate;

    public function __construct(WebAppService $webAppService, Translate $translate)
    {
        $this->webAppService = $webAppService;
        $this->router = $webAppService->router;
        $this->response = $webAppService->response;
        $this->translate = $translate;
    }

    /**
     * @inheritDoc
     */
    public function getSubscriptions(): array
    {
        return [
            new EventSubscription('app.http', [$this, 'processLocale'], 10)
        ];
    }

    /**
     * Set locale based on URL locale query parameter or cookie, and redirect if needed.
     *
     * @return void
     * @throws InvalidStatusCodeException
     */
    public function processLocale(): void
    {

        if (App::getConfig('webapp.locale.enabled') !== true) {
            return;
        }

        try {
            $route = $this->router->resolve();
        } catch (DispatchException) {
            $route = [];
        }

        if (Arr::get($route, 'params.' . App::getConfig('webapp.locale.routes.exclude.param', 'locale_exclude'), false)) {
            return;
        }

        $request_host = Request::getRequest('host');
        $request_path = Request::getRequest('path');

        foreach (App::getConfig('webapp.locale.routes.exclude.hosts', []) as $host) {
            if ($request_host == $host) {
                return;
            }
        }

        foreach (App::getConfig('webapp.locale.routes.exclude.paths', []) as $path) {
            if ($request_path == $path) {
                return;
            }
        }

        $valid_locales = App::getConfig('webapp.locale.valid', []);
        $cookie_name = App::getConfig('webapp.locale.cookie.name', 'locale');

        $force = false;

        if (Request::hasQuery($cookie_name)
            && in_array(Request::getQuery($cookie_name), $valid_locales)) {

            if (Cookie::get($cookie_name) !== Request::getQuery($cookie_name)) {
                $force = true;
            }

            Cookie::set($cookie_name, Request::getQuery($cookie_name), App::getConfig('webapp.locale.cookie.duration', 43200));
            $this->translate->setLocale(Request::getQuery($cookie_name));

        } else if ($this->getLocaleFromUrl()) { // If exists in URL

            $locale = $this->getLocaleFromUrl();

            if (Cookie::get($cookie_name) !== $locale) {

                $force = true;

                Cookie::set($cookie_name, $locale, App::getConfig('webapp.locale.cookie.duration', 43200));
            }

            $this->translate->setLocale($locale);

        } else if (Cookie::has($cookie_name)
            && in_array(Cookie::get($cookie_name), $valid_locales)) {

            $this->translate->setLocale(Cookie::get($cookie_name));

        } else { // Use default locale
            Cookie::set($cookie_name, $this->translate->getLocale(), App::getConfig('webapp.locale.cookie.duration', 43200));
        }

        if (App::getConfig('webapp.locale.routes.redirect')) {
            $this->updateRouteWithLocale($this->translate->getLocale(), $force);
        }

    }

    /**
     * Return valid locale in URL, if existing.
     *
     * @return string|null
     */
    private function getLocaleFromUrl(): ?string
    {

        $request_arr = Request::getRequest();

        $prefix_diff = $this->getPrefixDiff($request_arr['path']);
        $segments = explode('/', $prefix_diff);

        if (in_array($segments[0], App::getConfig('webapp.locale.valid', []))) { // Valid locale exists in request path
            return $segments[0];
        }

        return null;

    }

    /**
     * Get difference between request path and route prefix.
     *
     * @param string $path
     * @return string
     */
    private function getPrefixDiff(string $path): string
    {

        $prefix = App::getConfig('router.route_prefix', '/');

        if ($prefix == '' || $prefix == '/') {
            return trim($path, '/');
        }

        return trim(str_replace($prefix, '', $path), '/');

    }

    /**
     * Redirect.
     *
     * @param array $request_arr
     * @param string $redirect_path
     * @return void
     * @throws InvalidStatusCodeException
     */
    #[NoReturn] private function redirectTo(array $request_arr, string $redirect_path): void
    {

        $redirect_to = $request_arr['protocol'] . str_replace('//', '/', $request_arr['host'] . '/' . $redirect_path);

        if (!empty($request_arr['query'])) {
            unset($request_arr['query']['locale']);
            $redirect_to .= '?' . http_build_query($request_arr['query']);
        }

        $this->response->redirect($redirect_to);
        exit;

    }

    /**
     * Redirect request with locale if not already existing.
     *
     * @param string $locale
     * @param bool $force
     * @return void
     * @throws InvalidStatusCodeException
     */
    private function updateRouteWithLocale(string $locale, bool $force = false): void
    {

        $request_arr = Request::getRequest();

        $prefix_diff = $this->getPrefixDiff($request_arr['path']);
        $segments = explode('/', $prefix_diff);

        if (in_array($segments[0], App::getConfig('webapp.locale.valid', []))) { // Valid locale exists in request path

            if ($segments[0] != $locale || $force === true) {

                $segments[0] = $locale;

                $redirect_path = trim(App::getConfig('router.route_prefix'), '/') . '/' . trim(implode('/', $segments), '/');

                $this->redirectTo($request_arr, $redirect_path);

            }

            // Locale already exists in URL - no action required

        } else {

            $redirect_path = trim(App::getConfig('router.route_prefix'), '/') . '/' . $locale . '/' . $prefix_diff;

            $this->redirectTo($request_arr, $redirect_path);

        }

    }

}