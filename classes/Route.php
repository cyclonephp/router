<?php
namespace cyclonephp\router;
use cyclonephp\http\Request;

/**
 * @author Bence Erős <crystal@cyclonephp.org>
 */
class Route {

    private $uriPattern;

    private $uriRegex;
    
    private $paramRules;

    /**
     * @param $uriPattern
     */
    public function __construct($uriPattern, array $paramRules = array()) {
        $this->uriPattern = $uriPattern;
        $this->paramRules = $paramRules;
    }

    /**
     * Returns the extracted request parameters, or <code>null</code> on failure.
     *
     * @param Request $request
     * @return array
     */
    public function matches(Request $request) {
        $uri = $request->uri();
        $uri = ltrim($uri, '/');
        $regex = $this->uriRegex();
        $matches = [];
        if (preg_match_all($regex, $uri, $matches)) {
            $rval = [];
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $rval[$key] = $value[0];
                }
            }
            return $rval;
        }
        return null;
    }

    private function uriRegex() {
        if ($this->uriRegex === null) {
            $matches = [];
            preg_match_all('/{(?P<name>\w+)}/', $this->uriPattern, $matches);
            $uriRegex = '/^' . preg_replace('/[.\\+*?[^\\]$=!|\/:]/', '\\\\$0', $this->uriPattern) . '$/';
            foreach ($matches['name'] as $paramName) {
				$paramRule = isset($this->paramRules[$paramName]) ? $this->paramRules[$paramName] : '[^{}]+';
                $uriRegex = str_replace('{' . $paramName . '}', '(?P<' . $paramName . '>' . $paramRule . ')', $uriRegex);
            }
            $this->uriRegex = $uriRegex;
        }
        return $this->uriRegex;
    }

}
