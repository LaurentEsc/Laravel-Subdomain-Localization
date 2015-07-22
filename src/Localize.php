<?php namespace LaurentEsc\Localization;

use Illuminate\Http\Request;

class Localize
{

    /**
     * @var Request
     */
    protected $request;


    public function __construct()
    {
        $this->request = app()['request'];
    }

    /**
     * Set request
     *
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * If the detected locale is different from the url locale, we should redirect
     *
     * @return bool
     */
    public function shouldRedirect()
    {
        return $this->getCurrentLocale() != $this->getUrlLocale();
    }

    /**
     * Detect the current locale:
     *
     * - parsing the requested URL
     * - checking cookies
     * - using browser parameters
     * - using application settings
     */
    public function detectLocale()
    {

        // Get the current locale from the URL
        $locale = $this->getUrlLocale();

        // Get the current locale from the cookies
        if (!$this->isLocaleAvailable($locale) && $this->isCookieLocalizationEnabled()) {
            $locale = $this->getCookieLocale();
        }

        // Get the current locale from the browser
        if (!$this->isLocaleAvailable($locale) && $this->isBrowserLocalizationEnabled()) {
            $locale = $this->getBrowserLocale();
        }

        // Get the current locale from the application settings
        if (!$this->isLocaleAvailable($locale)) {
            $locale = $this->getFallbackLocale();
        }

        $this->setLocale($locale);
    }

    /**
     * Get available locales from package config
     *
     * @return array
     */
    public function getAvailableLocales()
    {
        return app()['config']->get('localization.available_locales');
    }

    /**
     * Get cookie localization status from package config
     *
     * @return array
     */
    protected function isCookieLocalizationEnabled()
    {
        return app()['config']->get('localization.cookie_localization');
    }

    /**
     * Get browser localization status from package config
     *
     * @return array
     */
    protected function isBrowserLocalizationEnabled()
    {
        return app()['config']->get('localization.browser_localization');
    }

    /**
     * Set cookie and application locale
     *
     * @param $locale
     */
    protected function setLocale($locale)
    {
        app()->setLocale($locale);

        if ($locale != $this->getCookieLocale() && $this->isCookieLocalizationEnabled()) {
            $this->setCookieLocale($locale);
        }
    }

    /**
     * Get current application locale
     *
     * @return string
     */
    protected function getCurrentLocale()
    {
        return app()->getLocale();
    }

    /**
     * Get default locale
     *
     * @return mixed
     */
    protected function getFallbackLocale()
    {
        return app()['config']->get('app.fallback_locale');
    }

    /**
     * Get browser locale
     *
     * @return mixed
     */
    protected function getBrowserLocale()
    {
        return $this->request->getPreferredLanguage($this->getAvailableLocales());
    }

    /**
     * Get locale from the url
     *
     * @return mixed
     */
    protected function getUrlLocale()
    {
        $segments = explode('.', $this->request->getHttpHost());

        return $segments[0];
    }

    /**
     * Set cookie locale
     *
     * @param $locale
     */
    protected function setCookieLocale($locale)
    {
        app()['cookie']->queue(app()['cookie']->forever(app()['config']->get('localization.cookie_name'), $locale));
    }

    /**
     * Get cookie locale
     *
     * @return mixed
     */
    protected function getCookieLocale()
    {
        return $this->request->cookie(app()['config']->get('localization.cookie_name'));
    }

    /**
     * Check if the given locale is accepted by the application
     *
     * @param $locale
     * @return bool
     */
    protected function isLocaleAvailable($locale)
    {
        return in_array($locale, $this->getAvailableLocales());
    }

}