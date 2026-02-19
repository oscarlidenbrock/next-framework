<?php

namespace Core\Controller;

class TestController extends \Core\Class\Controller
{
    public function default() {
        print "hello from DEFAULT action in TestController";
    }

    public function edit($var2, $var1) {
        $this->layout("html")->render('test/edit', ['var1' => $var1, 'var2' => $var2]);
    }
}