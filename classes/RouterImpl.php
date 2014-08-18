<?php
namespace cyclonephp\router;

use cyclonephp\http\Request;

class RouterImpl implements Router {
	
	private $protocol;
	
	private $host;
	
	private $port;
	
	private $basePath;

    private $paramsFactory;

    private $routes;
	
	public function __construct($host, $basePath = '/', $protocol = 'http', $port = 80,
            RoutingParamsFactory $paramsFactory,
            array $routes) {
		$this->host = $host;
		$this->basePath = $basePath;
		$this->protocol = $protocol;
		$this->port = $port;
        $this->paramsFactory = $paramsFactory;
        $this->routes = $routes;
	}
	
	public function getRoutingParams(Request $request) {
        foreach ($this->routes as $route) {
            $params = $route->matches($request);
            if ($params !== null) {
                return $this->createRoutingParamsForRouteMatch($params);
            }
        }
        throw new RoutingException("failed to find matching uri");
	}

    private function createRoutingParamsForRouteMatch(array $params) {
        $ns = isset($params['namespace']) ? $params['namespace'] : null;
        $controller = isset($params['controller']) ? $params['controller'] : null;
        $action = isset($params['action']) ? $params['action'] : null;
        unset($params['namespace']);
        unset($params['controller']);
        unset($params['action']);
        return $this->paramsFactory->create($ns, $controller, $action, $params);
    }
	
}
