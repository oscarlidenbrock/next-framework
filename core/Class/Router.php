<?php


namespace Core\Class;

use Symfony\Component\Yaml\Yaml;

class Router
{
    private $routes = [];
    public function __construct() {
        $modules = core()->config['modules'];

        /* Parse modules routes */
        foreach ($modules as $module => $moduleFolder) {
            $modulePath = dirname(__FILE__).'/../../modules/'.$moduleFolder;

            if (file_exists($modulePath.'/config/routes.yml')) {
                $routes = Yaml::parseFile($modulePath.'/config/routes.yml');

                foreach ($routes as $regex => $route) {
                    $this->routes[$regex] = [
                        'module' => $module,
                        'route' => $route,
                    ];
                }
            }
        }

        /* Get actual route and method */
        $currentRoute = $_SERVER['REQUEST_URI'];
        $currentMethod = $_SERVER['REQUEST_METHOD'];

        /* Parse routes */
        foreach ($this->routes as $regex => $route) {
            $methodValid = false;

            if (isset($route["route"]["method"])) {
                if (is_array($route["route"]["method"])) {
                    foreach ($route["route"]["method"] as $method) {
                        if ($currentMethod == trim(strtoupper($method))) {
                            $methodValid = true;
                            break;
                        }
                    }
                } else {
                    if ($currentMethod == trim(strtoupper($route["route"]["method"]))) $methodValid = true;
                }
            } else {
                /* If method is not defined, allow GET */
                if ($currentMethod == "GET") $methodValid = true;
            }

            pre($methodValid);
        }
    }
}