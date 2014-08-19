<?php
use cyclonephp\router\RouterImpl;
use cyclonephp\router\RoutingParamsFactory;
use cyclonephp\router\HelloController;
use DI\Container;

return [
	'controller.hello' => DI\factory(function(Container $c) {
		return new HelloController($c->get('controller.hello.router'));
	}),
	'controller.hello.router' => new RouterImpl(
		DI\link('controller.hello.router.host'),
		'/',
		'http',
		80,
		new RoutingParamsFactory('', '', ''),
		array()),
	'controller.hello.router.host' => 'example.org'
];
