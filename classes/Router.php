<?php
namespace cyclonephp\router;

use cyclonephp\http\Request;

interface Router {
	
	/**
	 * Extracts routing parameters from a HTTP request.
	 * 
	 * @return RoutingParams the parameters extracted from the <code>$request</code>
	 */
	public function getRoutingParams(Request $request);
	
}
