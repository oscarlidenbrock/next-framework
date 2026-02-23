<?php

namespace Core;

use Core\Class\Extend\Module as ModuleExtend;
class Module extends ModuleExtend
{
    public function hook_init() {
        print "hello from module hook_init";
    }
}