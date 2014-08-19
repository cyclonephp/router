<?php
namespace cyclonephp\router;

use cyclonephp\http\Request;
use cyclonephp\http\Response;
use DI\FactoryInterface;
use DI\ContainerBuilder;

class DefaultRequestDispatcherTest extends \PHPUnit_Framework_TestCase {
	
	private static function getContainer() {
		$rval = new ContainerBuilder;
		$rval->addDefinitions(__DIR__ . \DIRECTORY_SEPARATOR . 'dispatcher-test-config.php');
		return $rval->build();
	}
	
	private static function defaultRequest() {
		return Request::builder()->uri('foo')->method(Request::METHOD_GET)->build();
	}
	
	private static function routingParams($controller = 'foo', $action = 'bar') {
		return new RoutingParams(null, $controller, $action, array());
	}
	
	public function testParamsExplicit() {
		$request = self::defaultRequest();
		$params = self::routingParams();
		$emptyResponse = new Response();
		$container = self::getContainer();
		$mockController = $this->expectActionInvocation('barAction', $request, $params, $emptyResponse);
		$container->set('controller.foo', $mockController);
		$subject = new DefaultRequestDispatcher($container);
		$actual = $subject->dispatch($request, $params);
		$this->assertSame($emptyResponse, $actual);
	}
	
	public function testLoadingWithDependencies() {
		$request = self::defaultRequest();
		$params = self::routingParams('hello', 'world');
		$subject = new DefaultRequestDispatcher(self::getContainer());
		$this->assertNull($subject->dispatch($request, $params));
	}
	
	/**
	 * 
	 * @expectedException cyclonephp\router\DispatcherException
	 */
	public function testMissingControllerDefinition() {
		$request = self::defaultRequest();
		$params = self::routingParams(null, null);
		$subject = new DefaultRequestDispatcher(self::getContainer());
		$subject->dispatch($request, $params);
	}
	
	public function testFallbackToHTTPMethodAction() {
		$request = self::defaultRequest();
		$params = self::routingParams('hello', null);
		$container = self::getContainer();
		$emptyResponse = new Response;
		$container->set('controller.hello', $this->expectActionInvocation('getAction', $request, $params, $emptyResponse));
		$subject = new DefaultRequestDispatcher($container);
		$this->assertSame($emptyResponse, $subject->dispatch($request, $params));
	 }
	 
	 public function testWithNamespaceParameter() {
		 $request = self::defaultRequest();
		 $params = new RoutingParams('mymodule', 'foo', 'bar', array());
		 $emptyResponse = new Response;
		 $container = self::getContainer();
		 $container->set('mymodule.controller.foo', $this->expectActionInvocation('barAction', $request, $params, $emptyResponse));
		 $subject = new DefaultRequestDispatcher($container);
		 $this->assertSame($emptyResponse, $subject->dispatch($request, $params));
	 }
	 
	 private function expectActionInvocation($actionMethod, Request $expectedRequest, RoutingParams $expectedRoutingParams,
			Response $returnedResponse) {
		$rval = $this->getMock('stdclass', [$actionMethod]);
		$rval->expects($this->once())
			->method($actionMethod)
			->with($this->equalTo($expectedRequest), $this->equalTo($expectedRoutingParams))
			->willReturn($returnedResponse);
		return $rval;
	 }

}
