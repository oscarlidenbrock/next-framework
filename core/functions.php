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