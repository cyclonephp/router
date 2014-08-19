<?php
namespace cyclonephp\router;

use cyclonephp\http\Request;

interface RequestDispatcher {
	
	public function dispatch(Request $request, RoutingParams $routingParams);
	
}
