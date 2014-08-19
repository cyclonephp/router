<?php
namespace cyclonephp\router;

use cyclonephp\http\Request;

use DI\FactoryInterface;

class DefaultRequestDispatcher implements RequestDispatcher {
	
	private $objectFactory;
	
	public function __construct(FactoryInterface $objectFactory) {
		$this->objectFactory = $objectFactory;
	}
	
	private function getContainerKey(RoutingParams $routingParams) {
		$controllerDefinition = $routingParams->controller();
		if (empty($controllerDefinition))
			throw new DispatcherException('no controller definition in $routingParams');
			
		$key = 'controller.' . $controllerDefinition;
		$ns = $routingParams->ns();
		if (!empty($ns)) {
			$key = $ns . '.' . $key;
		}
		return $key;
	}
	
	public function dispatch(Request $request, RoutingParams $routingParams) {
		$controllerInstance = $this->objectFactory->get($this->getContainerKey($routingParams));
		$actionDefinition = $routingParams->action();
		if (empty($actionDefinition)) {
			$actionDefinition = strtolower($request->method());
		}
		$actionMethod = $actionDefinition . 'Action';
		return $controllerInstance->$actionMethod($request, $routingParams);
	}
	
}
