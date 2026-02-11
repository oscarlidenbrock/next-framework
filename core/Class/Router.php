<?php


namespace Core\Class;

use Symfony\Component\Yaml\Yaml;

class Router
{
    private $routes = [];
    public function __construct() {
        $modules = core()->config['modules'];

        foreach ($modules as $module) {
            if (isset($module['routes'])) {
                $this->routes = array_merge($this->routes, $module['routes']);
            }
        }
    }
}