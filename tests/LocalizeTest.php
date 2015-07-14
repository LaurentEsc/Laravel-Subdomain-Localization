<?php

class LocalizeTest extends TestCase
{

    protected $pathLocalized = 'localized';
    protected $pathNotLocalized = 'not-localized';

    protected function getPackageProviders($app)
    {
        return [
            'LaurentEsc\Localization\LocalizationServiceProvider'
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Localize' => 'LaurentEsc\Localization\Facades\Localize',
            'Router' => 'LaurentEsc\Localization\Facades\Router',
        ];
    }

    /**
     * It returns the available locales
     *
     * @test
     */
    public function it_returns_the_available_locales()
    {
        $availableLocales = ['en', 'es', 'fr', 'de'];

        $this->app['config']->set('localization.available_locales', $availableLocales);

        $this->assertEquals($availableLocales, app('localization.localize')->getAvailableLocales());
    }

    /**
     * It should not redirect a non-localized route
     *
     * @test
     */
    public function it_does_not_redirect_a_non_localized_route()
    {
        $this->sendRequest('GET', $this->pathNotLocalized);

        $this->assertResponseOk();
    }

    /**
     * It should not redirect if the locale is not missing
     *
     * @test
     */
    public function it_does_not_redirect_if_locale_is_not_missing()
    {
        $this->sendRequest('GET', $this->pathLocalized, 'de');

        $this->assertEquals(app()->getLocale(), 'de');

        $this->assertFalse(app('localization.localize')->shouldRedirect());

        $this->assertResponseOk();
    }

    /**
     * It detects and sets the locale from the url
     *
     * @test
     */
    public function it_detects_and_sets_the_locale_from_the_url()
    {
        $this->sendRequest('GET', $this->pathLocalized, 'de');

        $this->assertEquals($this->app->getLocale(), 'de');

        $this->assertFalse(app('localization.localize')->shouldRedirect());

        $this->assertResponseOk();
    }

    /**
     * It detects and sets the locale from the cookies
     *
     * @test
     */
    public function it_detects_and_sets_the_locale_from_the_cookies()
    {
        $this->sendRequest('GET', $this->pathLocalized, null, [], ['locale' => 'de']);

        $this->assertEquals($this->app->getLocale(), 'de');

        $this->assertTrue(app('localization.localize')->shouldRedirect());

        $this->assertResponseStatus(302);

        $this->assertRedirectedTo($this->getUri($this->pathLocalized, 'de'));

        $this->refreshApplication();

        // Disable cookie localization
        app('config')->set('localization.cookie_localization', false);

        $this->sendRequest('GET', $this->pathLocalized, null, [], ['locale' => 'de']);

        $this->assertEquals($this->defaultLocale, $this->app->getLocale());

        $this->assertTrue(app('localization.localize')->shouldRedirect());

        $this->assertResponseStatus(302);

        $this->assertRedirectedTo($this->getUri($this->pathLocalized, $this->defaultLocale));
    }

    /**
     * It detects and sets the locale from the browser language settings
     *
     * @test
     */
    public function it_detects_and_sets_the_locale_from_the_browser()
    {
        $this->sendRequest('GET', $this->pathLocalized, null, [], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'de']);

        $this->assertEquals($this->app->getLocale(), 'de');

        $this->assertTrue(app('localization.localize')->shouldRedirect());

        $this->assertResponseStatus(302);

        $this->assertRedirectedTo($this->getUri($this->pathLocalized, 'de'));

        $this->refreshApplication();

        // Disable cookie localization
        app('config')->set('localization.browser_localization', false);

        $this->sendRequest('GET', $this->pathLocalized, null, [], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'de']);

        $this->assertEquals($this->defaultLocale, $this->app->getLocale());

        $this->assertTrue(app('localization.localize')->shouldRedirect());

        $this->assertResponseStatus(302);

        $this->assertRedirectedTo($this->getUri($this->pathLocalized, $this->defaultLocale));
    }

    /**
     * It detects and sets the locale from the default locale setting
     *
     * @test
     */
    public function it_detects_and_sets_the_locale_from_the_config()
    {
        $this->sendRequest('GET', $this->pathLocalized);

        $this->assertEquals($this->defaultLocale, $this->app->getLocale());

        $this->assertTrue(app('localization.localize')->shouldRedirect());

        $this->assertResponseStatus(302);

        $this->assertRedirectedTo($this->getUri($this->pathLocalized, $this->defaultLocale));
    }

    /**
     * It responds with the cookie locale
     *
     * @test
     */
    public function it_responds_with_the_cookie_locale()
    {
        $response = $this->sendRequest('GET', $this->pathLocalized, 'de');

        $this->assertTrue($this->responseHasCookies($response, ['locale' => 'de']));
        $this->assertResponseOk();

        $this->refreshApplication();

        // Disable cookie localization
        app('config')->set('localization.cookie_localization', false);

        $response = $this->sendRequest('GET', $this->pathLocalized, 'de');

        $this->assertFalse($this->responseHasCookies($response, ['locale' => 'de']));
        $this->assertResponseOk();
    }

}
