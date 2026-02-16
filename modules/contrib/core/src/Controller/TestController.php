<?php

namespace Core\Controller;

class TestController
{
    public function default() {
        print "hello from DEFAULT action in TestController";
    }

    public function edit($var2, $var1) {
        $args = func_get_args();
        pre($args);
    }
}