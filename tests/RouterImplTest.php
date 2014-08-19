<?php
namespace cyclonephp\router;

use cyclonephp\http\Request;

class RouterImplTest extends \PHPUnit_Framework_TestCase {

    /**
     * @return RouterImpl
     */
    private function absPathSubject($routesByUriPattern) {
        $routes = [];
        foreach ($routesByUriPattern as $uriPattern) {
            $routes []= new Route($uriPattern);
        }
        return new RouterImpl('example.org', '/', 'http', 80, new RoutingParamsFactory('', 'index', 'welcome'), $routes);
    }

    public function testForRootPath() {
        $actual = $this->absPathSubject([
             '{controller}/{action}'
        ])->getRoutingParams(Request::builder()
            ->method(Request::METHOD_GET)
            ->uri('/hello/world')
            ->build());
        $this->assertEquals('hello', $actual->controller());
        $this->assertEquals('world', $actual->action());
        $this->assertEmpty($actual->additionalParams());
    }

    public function testNumericParam() {
        $actual = $this->absPathSubject([
            'user/{id}'
        ])->getRoutingParams(Request::builder()
                ->method(Request::METHOD_GET)
                ->uri('/user/12')
                ->build());
        $this->assertEquals(12, $actual->additionalParams()['id']);
    }

}
