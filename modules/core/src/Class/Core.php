<?php

namespace Core\Class;

use Symfony\Component\Yaml\Yaml;

class Core
{
    public $config;
    private $modules = [];

    public $router;
    public $request;
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
        /* Core class load */
        $this->request = new Request();
        $this->router = new Router();
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
     * Return module config
     * @param $moduleKey
     * @return mixed
     */
    public function getModuleConfig($moduleKey) {
        return $this->modules[$moduleKey];
    }
}