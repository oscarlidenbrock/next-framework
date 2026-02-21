<?php

namespace Core\Class;

use Core\Service\Router;
use Symfony\Component\Yaml\Yaml;

class Core
{
    private $config;
    private $modules = [];
    private $services = [];
    public function __construct($config) {
        $this->config = $config;
    }

    /**
     * Init core function
     * @return void
     */
    public function init() {
        /* Set modules list */
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(MODULES_PATH, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() === 'module.php') {
                $modulePath = dirname($file->getPathname());
                $segments = explode('/', $modulePath);
                $moduleKey = end($segments);

                $moduleNamespace = $moduleKey;
                $segments = explode('_', $moduleNamespace);
                foreach ($segments as &$segment) {
                    $segment = ucfirst($segment);
                }
                $moduleNamespace = implode('', $segments);

                if (file_exists($modulePath.'/config/module.yml')) {
                    $moduleConfig = Yaml::parseFile($modulePath.'/config/module.yml');
                    $this->modules[$moduleKey] = array_merge([
                        'path' => $modulePath,
                        'namespace' => $moduleNamespace
                    ], $moduleConfig);
                } else {
                    $this->error('el modulo o su configuración no existen');
                }
            }
        }

        /* Parse modules */
        foreach ($this->modules as $moduleKey => $moduleConfig) {
            $modulePath = MODULES_PATH.'/'.$moduleKey;

            /* Parse module services */
            if (file_exists($modulePath.'/config/services.yml')) {
                $moduleServices = Yaml::parseFile($modulePath.'/config/services.yml');

                if (count($moduleServices)) {
                    foreach ($moduleServices as $serviceName => $service) {
                        $segments = explode('\\', $service['class']);
                        unset($segments[0]);
                        unset($segments[1]);
                        $segments = implode('/', $segments);

                        require_once(MODULES_PATH.'/'.$moduleKey.'/src/Service/'.$segments.'.php');
                        $this->services[$serviceName] = new $service['class']();
                    }
                }
            }
        }

        /* Run Controller */
        $this->service('router')->init();
    }

    /**
     * Core error handler
     * @param $message
     * @return void
     */
    public function error($message) {
        /* TODO: make standar errors */
        die($message);
    }

    /**
     * Return service
     * @param $serviceName
     * @return mixed|null
     */
    public function service($serviceName) {
        if (isset($this->services[$serviceName])) {
            return $this->services[$serviceName];
        } else {
            return null;
        }
    }

    /**
     * Return module config
     * @param $moduleKey
     * @return mixed
     */
    public function getModules() {
        return $this->modules;
    }
}