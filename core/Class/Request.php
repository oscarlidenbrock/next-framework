<?php

namespace Core\Class;

use Symfony\Component\Yaml\Yaml;

class Request
{
    private $request;
    private $files;

    public function __construct() {
        /* set global variables into request object */
        $this->request = array_merge($_POST, $_GET);
        $this->files = $_FILES;

        /* unset global variables */
        unset($_GET);
        unset($_POST);
        unset($_FILES);
        unset($_REQUEST);
    }

    public function set($key, $value) {
        $this->request[$key] = $value;
    }

    public function get($key) {
        return $this->request[$key];
    }
}