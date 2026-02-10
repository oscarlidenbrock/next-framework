<?php

namespace Core\Class;

use Symfony\Component\Yaml\Yaml;

class Core
{
    private $config;
    private $modules = [];
    public function __construct($config)
    {
        $this->config = $config;

        /* Parse modules */
        if (isset($this->config['modules'])) {
            foreach ($this->config['modules'] as $moduleKey => $moduleFolder) {
                $modulePath = dirname(__FILE__).'/../../modules/'.$moduleFolder;

                if (file_exists($modulePath.'/module.config.yml')) {
                    $moduleConfig = Yaml::parseFile($modulePath.'/module.config.yml');
                    $this->modules[$moduleKey] = [
                        'config' => $moduleConfig
                    ];
                } else {
                    /* TODO: Change error to standar errors */
                    die('el modulo o su configuración no existen');
                }
            }
        }
    }
}