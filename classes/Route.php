<?php
namespace cyclonephp\router;
use cyclonephp\http\Request;

/**
 * @author Bence Erős <crystal@cyclonephp.org>
 */
class Route {

    private $uriPattern;

    private $uriRegex;

    /**
     * @param $uriPattern
     */
    public function __construct($uriPattern) {
        $this->uriPattern = $uriPattern;
    }

    /**
     * Returns the extracted request parameters, or <code>null</code> on failure.
     *
     * @param Request $request
     * @return array
     */
    public function matches(Request $request) {
        $uri = $request->uri();
        $uri = trim($uri, '/');
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
            $uriRegex = '/' . str_replace('/', '\/', $this->uriPattern) . '/';
            foreach ($matches['name'] as $paramName) {
                $uriRegex = str_replace('{' . $paramName . '}', '(?P<' . $paramName . '>\w+)', $uriRegex);
            }
            $this->uriRegex = $uriRegex;
        }
        return $this->uriRegex;
    }

}
