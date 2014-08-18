<?php
namespace cyclonephp\router;


/**
 * The routing parameters which are extracted from a HTTP request using a Router, and used by a Dispatcher to execute a
 * controller, which generates the HTTP response.
 * 
 * The extracted routing parameter (ie. the properties of this class) may or may not be used by the Dispatcher in an
 * implementation-specific way.
 * 
 */
class RoutingParams {
	
	private $ns;
	
	private $controller;
	
	private $action;

    private $additionalParams;
	
	public function __construct($ns, $controller, $action, array $additionalParams) {
		$this->ns = $ns;
		$this->controller = $controller;
		$this->action = $action;
        $this->additionalParams = $additionalParams;
	}
	
	public function ns() {
		return $this->ns;
	}
	
	public function controller() {
		return $this->controller;
	}
	
	public function action() {
		return $this->action;
	}

    public function additionalParams() {
        return $this->additionalParams;
    }
	
}
