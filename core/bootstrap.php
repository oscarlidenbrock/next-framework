<?php

use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile('../config/app.yml');
$workspace = null;

/* Check workspaces */
foreach ($config['workspaces'] as $workspaceName => $workspaceConfig) {
    /* check hosts */
    if (preg_match('/'.$workspaceConfig['host'].'/', $_SERVER['HTTP_HOST'])) {
        $workspace = $workspaceName;
        $config = $workspaceConfig;
        break;
    }
}

if (!$workspace) {
    die("No workspace defined in config/app.yml");
}

pre($config, true);
/**
 * Autoload Class Function
 */
spl_autoload_register(function($classPath) {
    $segments = explode('\\', $classPath);
    $className = array_pop($segments);

    /* If first value is "Core", change path to core folder */
    if ($segments[0] == 'Core') {
        $segments[0] = 'core';
    } else {
        /* else, include modules folder in path */
        $segments[0] = 'modules/'.strtolower($segments[0]);
    }

    $classPath = dirname(__FILE__).'/../'.implode('/', $segments);
    $classPath .= '/'.$className.'.php';

    if (file_exists($classPath)) {
        require_once($classPath);
    } else {
        error();
    }
});

/**
 * Debug function to print and object variables into "pre" tag.
 * @param $obj
 * @param boolean $die
 */
function pre(&$obj, $die = false) {
    echo '<pre>';
    var_dump($obj);
    echo '</pre>';
    if ($die) die();
}