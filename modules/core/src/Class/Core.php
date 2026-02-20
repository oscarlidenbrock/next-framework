<?php

namespace Core\Class;

use Core\Service\Router;
use Symfony\Component\Yaml\Yaml;

class Core
{
    public $config;
    private $modules = [];
    private $services = [];

    public $router;
    public function __construct($config) {
        $this->config = $config;

        /* Parse modules */
        if (isset($this->config['modules'])) {
            foreach ($this->config['modules'] as $moduleKey => $moduleEnabled) {
                if ($moduleEnabled) {
                    $modulePath = MODULES_PATH.'/'.$moduleKey;

                    if (file_exists($modulePath.'/config/module.yml')) {
                        $moduleConfig = Yaml::parseFile($modulePath.'/config/module.yml');
                        $this->modules[$moduleKey] = [
                            'path' => $modulePath,
                            'config' => $moduleConfig
                        ];
                    } else {
                        $this->error('el modulo o su configuración no existen');
                    }
                }
            }
        }
    }

    /**
     * Init core function
     * @return void
     */
    public function init() {
        /* Parse modules */
        if (isset($this->config['modules'])) {
            foreach ($this->config['modules'] as $moduleKey => $moduleEnabled) {
                if ($moduleEnabled) {
                    $modulePath = MODULES_PATH.'/'.$moduleKey;

                    /* Parse module services */
                    if (file_exists($modulePath.'/config/services.yml')) {
                        $moduleServices = Yaml::parseFile($modulePath.'/config/services.yml');

                        if (count($moduleServices)) {
                            foreach ($moduleServices as $serviceName => $service) {
                                // $service['class'] = '\\'.$service['class'];
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
            }
        }
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
    public function getModuleConfig($moduleKey) {
        return $this->modules[$moduleKey];
    }
}