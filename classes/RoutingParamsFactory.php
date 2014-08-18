<?php
namespace cyclonephp\router;

class RoutingParamsFactory {

    private $defaultNs;

    private $defaultController;

    private $defaultAction;

    public function __construct($defaultNs, $defaultController, $defaultAction) {
        $this->defaultNs = $defaultNs;
        $this->defaultController = $defaultController;
        $this->defaultAction = $defaultAction;
    }

    /**
     * @return RoutingParams
     */
    public function create($ns, $controller, $action, $additionalParams) {
        return new RoutingParams(
            empty($ns) ? $this->defaultNs : $ns,
            empty($controller) ? $this->defaultController : $controller,
            empty($action) ? $this->defaultAction : $action,
            $additionalParams
        );
    }

}
