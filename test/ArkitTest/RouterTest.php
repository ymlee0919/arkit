<?php

namespace ArkitTest;

use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{

    /**
     * @var \Arkit\Core\Control\Routing\Router
     */
    private $router;

    public function setUp() : void
    {
        parent::setUp();
        // Build router
        $this->router = new \Arkit\Core\Control\Routing\Router();
        // Build file source
        $source = dirname(__FILE__) . '/files/router.yaml';
        // Read the file
        $roules = \Arkit\App::readConfig($source);
        // Set the routes
        $this->router->setRules($roules);
    }

    public function urlProvider() : array
    {
        return [
            ['GET', '/'],
            ['GET', '/dashboard'],
            ['GET', '/app-systems/list'],
            ['GET', '/app-systems/new'],
            ['POST', '/systems/add'],
            ['GET', '/app-systems/24/info'],
            ['GET', '/app-systems/312/edit'],
            ['POST', '/app-systems/update'],
            ['GET', '/app-systems/list/filter?start=0&end=10'],
            ['GET', '/app-systems/list/filter?end=0&limit=10&name=algo'],
        ];
    }

    /**
     * @covers \Arkit\Core\Control\Routing\Router
     * @dataProvider urlProvider
     */
    public function testValidUrl($method, $url)
    {
        $result = $this->router->route($url, $method);
        $this->assertInstanceOf('Arkit\\Core\\Control\\Routing\\RoutingHandler', $result, "Invalid url for route: [{$method}] {$url}" );
    }

    public function invalidUrlProvider() : array
    {
        return [
            ['GET', ''],
            ['GET', '//'],
            ['GET', '/dashboard/'],
            ['POST', '/systems/list'],
            ['POST', '/app-systems//new'],
            ['GET', '/app-systems/34-23/edit'],
            ['GET', '/app-systems/aP312/edit'],
            ['GET', '/app-systems/update/'],
            ['GET', '/app-systems/list/filter?start=0&ends=10'],
            ['GET', '/app-systems/list/filter?start=0&end=10&name=algo&callback=route.callback'],
        ];
    }

    /**
     * @covers \Arkit\Core\Control\Routing\Router
     * @dataProvider invalidUrlProvider
     */
    public function testInvalidUrl($method, $url)
    {
        $result = $this->router->route($url, $method);
        $this->assertNull($result, "Valid url for route: [{$method}] {$url}" );
    }

    /**
     * @covers \Arkit\Core\Control\Routing\Router
     */
    public function testBuildingSimpleUrl()
    {
        $url = $this->router->buildUrl('systems.update');
        $this->assertEquals('/app-systems/update', $url);
    }

    /**
     * @covers \Arkit\Core\Control\Routing\Router
     */
    public function testBuildingUrlWithParameters()
    {
        $url = $this->router->buildUrl('systems.filter', ['start' => 10, 'end' => 50]);
        $this->assertEquals('/app-systems/list/filter?start=10&end=50', $url);
    }

}