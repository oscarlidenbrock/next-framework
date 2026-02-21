<?php

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

/**
 * Link to main web object.
 * @return \modules\core\src\Class\Core
 */
function &core() {
    global $core;
    return $core;
}

/**
 * Check if exists multiple paths and return first found.
 * @param $paths
 * @return string|null
 */
function files_path_check($paths = []) {
    foreach ($paths as $path) {
        if (file_exists($path)) {
            return $path;
        }
    }

    return null;
}

/**
 * Create a variable length and alphanumeric token.
 * @param int $length
 * @param string $chars
 * @return string
 */
function token($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890') {
    $string = '';
    for ($i = 1; $i <= $length; $i++) $string .= $chars[rand(0, (strlen($chars) - 1))];
    return $string;
}