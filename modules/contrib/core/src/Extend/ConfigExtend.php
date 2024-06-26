<?php

namespace NextFramework\Core\Extend;

use Symfony\Component\Yaml\Yaml;

class ConfigExtend {
    private $values = [];

    public function init() {
        /* Parse environment configs and set config by host */
        $actualHost = $_SERVER['HTTP_HOST'];

        $configFiles = scandir(APP_ROOT.'/config/environment');
        foreach ($configFiles as $configFile) {
            if ($configFile != '.' && $configFile != '..') {
                $data = YAML::parse(file_get_contents(APP_ROOT.'/config/environment/'.$configFile));

                if (isset($data['environment']) && isset($data['environment']['hosts'])) {
                    foreach ($data['environment']['hosts'] as $key => $host) {
                        if ($host == $actualHost) {
                            /* set environment const */
                            $environment = trim(str_replace('.yaml', '', $configFile));
                            define('APP_ENV', $environment);

                            /* Load config and exit foreach */
                            $this->values = $data;
                        }
                    }
                }
            }
        }
    }

    public function get($key) {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        } else {
            return null;
        }
    }

    public function set($key, $value) {
        $this->values[$key] = $value;
    }
}