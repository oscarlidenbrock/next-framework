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