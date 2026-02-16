<?php

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
        /* if first value is "Core", maybe can referer to core module */
        if ($segments[0] == 'core') {
            /* get module path */
            $modulePath = core()->getModuleConfig($segments[0])["path"].'/src';
            unset($segments[0]);

            $classPath = $modulePath.'/'.implode('/', $segments);
            $classPath .= '/'.$className.'.php';

            if (file_exists($classPath)) {
                require_once($classPath);
            } else {
                error();
            }
        } else {
            error();
        }
    }
});