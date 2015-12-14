<?php

class RouterTest extends TestCase
{

    protected $routeNameWithoutParameter = 'Localize::routes.good_morning';
    protected $dePathWithoutParameter = 'guten-morgen';
    protected $enPathWithoutParameter = 'good-morning';

    protected $routeNameWithParameter = 'Localize::routes.hello_user';
    protected $dePathWithParameter = 'hallo/{username}';
    protected $enPathWithParameter = 'hello/{username}';
    protected $dePathWithParameter1 = 'hallo/samplename';
    protected $enPathWithParameter1 = 'hello/samplename';
    protected $routeParameters = ['username' => 'samplename'];

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
     * It translates routes
     *
     * @test
     */
    public function it_reaches_translated_routes()
    {
        $this->sendRequest('GET', $this->dePathWithoutParameter, 'de');
        $this->assertResponseOk();

        $this->sendRequest('GET', $this->enPathWithoutParameter, 'en');
        $this->assertResponseOk();
    }

    /**
     * It returns a redirect URL
     *
     * @test
     */
    public function it_returns_a_redirect_url()
    {
        $this->setRequestContext('GET', $this->dePathWithoutParameter, null, [], ['locale' => 'de']);
        $this->assertEquals($this->getUri($this->dePathWithoutParameter, 'de'),
            app('localization.router')->getRedirectURL());

        $this->setRequestContext('GET', $this->enPathWithoutParameter, null, [], ['locale' => 'en']);
        $this->assertEquals($this->getUri($this->enPathWithoutParameter, 'en'),
            app('localization.router')->getRedirectURL());
    }


    /**
     * It translates the current route
     *
     * @test
     */
    public function it_translates_the_current_route()
    {
        $this->sendRequest('GET', $this->dePathWithoutParameter, 'de');
        $this->assertEquals($this->getUri($this->enPathWithoutParameter, 'en'),
            app('localization.router')->current('en'));

        $this->refresh();

        $this->sendRequest('GET', $this->enPathWithParameter1, 'en');
        $this->assertEquals($this->getUri($this->dePathWithParameter1, 'de'),
            app('localization.router')->current('de'));
    }

    /**
     * It returns translated versions of the current route for all available locales
     *
     * @test
     */
    public function it_returns_translated_versions_of_the_current_route_for_available_locales()
    {
        $this->sendRequest('GET', $this->dePathWithoutParameter, 'de');
        $this->assertEquals([
            'en' => $this->getUri($this->enPathWithoutParameter, 'en')
        ], app('localization.router')->getCurrentVersions());

        $this->refresh();

        $this->sendRequest('GET', $this->enPathWithParameter1, 'en');
        $this->assertEquals([
            'en' => $this->getUri($this->enPathWithParameter1, 'en'),
            'de' => $this->getUri($this->dePathWithParameter1, 'de')
        ], app('localization.router')->getCurrentVersions(false));
    }

    /**
     * It resolves a translated route path
     *
     * @test
     */
    public function it_resolves_a_translated_route_path()
    {
        $this->setRequestContext('GET', '', 'de');
        $this->assertEquals($this->dePathWithoutParameter,
            app('localization.router')->resolve($this->routeNameWithoutParameter));

        $this->setRequestContext('GET', '', 'en');
        $this->assertEquals($this->enPathWithParameter,
            app('localization.router')->resolve($this->routeNameWithParameter));
    }

    /**
     * It translates a route into an url
     *
     * @test
     */
    public function it_translates_a_route_into_an_url()
    {

        $this->setRequestContext('GET', '');

        $this->assertEquals(
            $this->getUri($this->dePathWithoutParameter, 'de'),
            app('localization.router')->url($this->routeNameWithoutParameter, null, 'de')
        );

        $this->assertEquals(
            $this->getUri($this->enPathWithParameter1, 'en'),
            app('localization.router')->url($this->routeNameWithParameter, $this->routeParameters, 'en')
        );

        $this->setRequestContext('GET', '', 'de');

        $this->assertEquals(
            $this->getUri($this->dePathWithParameter1, 'de'),
            app('localization.router')->url($this->routeNameWithParameter, $this->routeParameters)
        );

        $this->setRequestContext('GET', '', 'en');

        $this->assertEquals(
            $this->getUri($this->enPathWithParameter1, 'en'),
            app('localization.router')->url($this->routeNameWithParameter, $this->routeParameters)
        );
    }

}
