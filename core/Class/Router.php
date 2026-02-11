<?php


namespace Core\Class;

use Symfony\Component\Yaml\Yaml;

class Router
{
    private $routes = [];
    public function __construct() {
        $modules = core()->config['modules'];

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
    }
}