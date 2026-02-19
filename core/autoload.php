<?php

/**
 * Autoload Class Function
 */
spl_autoload_register(function($classPath) {
    $segments = explode('\\', $classPath);
    $segments[0] = 'modules/'.strtolower($segments[0]).'/src';$segments[1] =
    $classPath = implode('/', $segments).'.php';

    if (file_exists(APP_PATH.'/'.$classPath)) {
        require_once(APP_PATH.'/'.$classPath);
    } else {
        error();
    }

});