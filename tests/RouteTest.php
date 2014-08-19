<?php
namespace cyclonephp\router;

use cyclonephp\http\Request;

class RouteTest extends \PHPUnit_Framework_TestCase {
	
	private static function routeForUriPattern($pattern) {
		return new Route($pattern);
	}
	
	private static function requestForUri($uri) {
		return Request::builder()->uri($uri)->build();
	}
	
	public function testSimpleUriMatch() {
		$this->assertEquals(
			[
				'controller' => 'hello',
				'action' => 'world'
			],
			self::routeForUriPattern('{controller}/{action}')->matches(self::requestForUri('/hello/world'))
		);
	}
	
	public function testWithUriPrefix() {
		$route = self::routeForUriPattern('pref{controller}');
		$this->assertNotNull($route->matches(self::requestForUri('prefcont')));
		$this->assertNull($route->matches(self::requestForUri('aprefcnt')));
	}
	
	public function testUriWithSuffix() {
		$route = self::routeForUriPattern('{param}suffix');
		$this->assertNotNull($route->matches(self::requestForUri('whateversuffix')));
		$this->assertNull($route->matches(self::requestForUri('whateversuffixxx')));
	}
	
	public function testSpecialCharInRoute() {
		$route = self::routeForUriPattern('{controller}::{action}');
		$this->assertEquals(['controller' => 'hello', 'action' => 'world'],
			$route->matches(self::requestForUri('hello::world')));
		$route = self::routeForUriPattern('{controller}?{action}');
		$this->assertEquals(['controller' => 'hello', 'action' => 'world'],
			$route->matches(self::requestForUri('hello?world')));
		$route = self::routeForUriPattern('{controller}/{filename}.{ext}');
		$this->assertEquals(['controller' => 'downloads', 'filename' => 'whatever', 'ext' => 'png'],
			$route->matches(self::requestForUri('/downloads/whatever.png')));
	}
	
	public function testWithCustomRegex() {
		$route = new Route('user/{userId}', array(
			'userId' => '\d+'
		));
		$this->assertEquals(['userId' => 42], $route->matches(self::requestForUri('/user/42')));
		$this->assertNull($route->matches(self::requestForUri('/user/aaa')));
		$route = new Route('user/{userId}', array(
			'userId' => '\d*'
		));
		$this->assertEquals(['userId' => 42], $route->matches(self::requestForUri('/user/42')));
		$this->assertEquals(['userId' => ''], $route->matches(self::requestForUri('/user/')));
		$route = new Route('{lang}/{title}', array(
			'lang' => 'hu|en'
		));
		$this->assertEquals(['lang' => 'hu', 'title' => 'hello-there'], $route->matches(self::requestForUri('/hu/hello-there')));
	}
	
	public function testCustomPredicate() {
		$route = new Route('user/{userId}', array(), array(
			function (Request $req) {
				return $req->method() == Request::METHOD_GET;
			}
		));
		$this->assertEquals(['userId' => 42], $route->matches(self::requestForUri('/user/42')));
		$this->assertNull($route->matches(Request::builder()->uri('/user/42')->method(Request::METHOD_POST)->build()));
	}
	
}
