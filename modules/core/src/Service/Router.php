<?php


namespace Core\Service;

use Symfony\Component\Yaml\Yaml;

class Router
{
    private $controller;
    private $routes = [];
    public function __construct() {
        // $modules = core()->config['modules'];
        $modules = ['core' => true];

        /* Parse modules routes */
        foreach ($modules as $moduleKey => $moduleEnabled) {
            $modulePath = MODULES_PATH.'/'.$moduleKey;

            if (file_exists($modulePath.'/config/routes.yml')) {
                $routes = Yaml::parseFile($modulePath.'/config/routes.yml');

                foreach ($routes as $routeKey => $route) {
                    $this->routes[$routeKey] = [
                        'module' => $moduleKey,
                        'route' => $route,
                    ];
                }
            }
        }

        /* Parse routes */
        foreach ($this->routes as $routeKey => $route) {
            $regex = $route['route']['path'];
            $currentMethod = $_SERVER['REQUEST_METHOD'];
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

            /* If method is not valid, continue to next route */
            if (!$methodValid) continue;

            /* check routes regex with params */
            $currentRoute = explode('?', $_SERVER['REQUEST_URI'])[0];
            $routeParams = [];
            $matches = null;

            if (isset($route['route']['params'])) {
                foreach($route['route']['params'] as $param => $value) {
                    $routeParams[] = $param;
                    $regex = str_replace('{'.$param.'}', '('.$value.')', $regex);
                }
            }

            /* Add optional slash to regex */
            $regex = '/'.$regex.'/?';
            $regex = str_replace('\/', '/', $regex);
            $regex = str_replace('/', '\/', $regex);

            /* Check if actual route matches */
            if (preg_match("/^$regex$/", $currentRoute, $matches)) {
                /* replace variables with their values */
                array_shift($matches);
                $controllerValues = [];

                foreach($routeParams as $key => $value) {
                    $controllerValues[$value] = $matches[$key];
                }

                /* Set controller/action/params in object */
                $this->controller = [
                    'method' => $_SERVER['REQUEST_METHOD'],
                    'controller' => $route['route']['controller'],
                    'action' => $route['route']['action'],
                    'parameters' => $controllerValues,
                ];

                /* Set params in request object */
                foreach ($controllerValues as $key => $value) {
                    core()->service('request')->set($key, $value);
                }

                /* Load controller */
                $moduleName = core()->getModuleConfig($route['module'])["config"]["module"]["name"];
                $className = '\\'.$moduleName.'\\Controller\\'.$route['route']['controller'].'Controller';

                $classObject = new $className();

                /* Call to action method */
                $return = call_user_func_array([$classObject, $route['route']['action']], $controllerValues);

                pre($return);
            }
        }
    }
}