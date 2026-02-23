<?php

namespace Core;

use Core\Class\Extend\Module as ModuleExtend;
class Module extends ModuleExtend
{
    function __construct() {
        print "hello from module";
    }
}