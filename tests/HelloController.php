<?php
namespace cyclonephp\router;

use cyclonephp\http\Request;

class HelloController {
	
	private $router;
	
	public function __construct(Router $router) {
		$this->router = $router;
	}
	
	public function worldAction(Request $request, RoutingParams $params) {
		return null;
	}
	
}
