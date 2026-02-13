<?php

namespace Core\Class;

use Symfony\Component\Yaml\Yaml;

class Request
{
    private $get;
    private $post;
    private $files;

    public function __construct() {
        /* set global variables into request object */
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;

        /* unset global variables */
        unset($_GET);
        unset($_POST);
        unset($_FILES);
    }
}