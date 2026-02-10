<?php

namespace Core\Class;

use PSpell\Config;

class Core
{
    private $config;
    public function __construct($config)
    {
        $this->config = $config;
    }
}